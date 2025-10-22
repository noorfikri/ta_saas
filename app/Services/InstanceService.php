<?php

namespace App\Services;

use App\Models\Instance;
use Aws\CloudFormation\CloudFormationClient;
use Aws\Credentials\Credentials;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InstanceService
{
    protected $cloudFormation;

    public function __construct()
    {
        $credentials = new Credentials(env('AWS_ACCESS_KEY_ID'), env('AWS_SECRET_ACCESS_KEY'));
        $this->cloudFormation = new CloudFormationClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => $credentials,
        ]);
    }

    public function provisionInstance(Instance $instance): ?string
    {
        $stackName = 'instance-' . $instance->id . '-' . Str::slug($instance->name);
        $templateBody = file_get_contents(base_path('cloud/template/instance_create.yml'));

        $parameters = [
            [
                'ParameterKey' => 'InstanceDBName',
                'ParameterValue' => 'db' . $instance->id,
            ],
            [
                'ParameterKey' => 'InstanceDBPassword',
                'ParameterValue' => Str::random(24),
            ],
            [
                'ParameterKey' => 'KeyName',
                'ParameterValue' => env('AWS_EC2_KEY_PAIR_NAME'),
            ],[
                'ParameterKey' => 'InstanceName',
                'ParameterValue' => $instance->name,
            ]
        ];

        try {
            $result = $this->cloudFormation->createStack([
                'StackName' => $stackName,
                'TemplateBody' => $templateBody,
                'Parameters' => $parameters,
                'Capabilities' => ['CAPABILITY_IAM'],
            ]);

            $instance->aws_stack_name = $stackName;
            $instance->aws_stack_id = $result['StackId'];
            $instance->status = 'creating';
            $instance->message = "pembuatan sistem sedang berjalan...";
            $instance->save();

            return $result['StackId'];

        } catch (\Exception $e) {
            $instance->status = 'failed';
            $instance->message = $e->getMessage();
            $instance->save();

            return null;
        }
    }

    public function deprovisionInstance(string $stackId): bool
    {
        try {
            $this->cloudFormation->deleteStack(['StackName' => $stackId]);

            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function getStackDetails(string $stackId): ?array
    {
        try {
            $result = $this->cloudFormation->describeStacks(['StackName' => $stackId]);
            return $result['Stacks'][0] ?? null;
        } catch (AwsException $e) {
            if ($e->getAwsErrorCode() === 'ValidationError' && str_contains($e->getMessage(), 'sudah dihapus')) {
                 return ['StackStatus' => 'DELETE_COMPLETE'];
            }

            return null;
        }
    }

    public function updateInstanceStatuses(): void
    {
        $instancesToUpdate = Instance::whereIn('status', ['creating', 'deleting'])->get();

        if ($instancesToUpdate->isEmpty()) {
            return;
        }

        foreach ($instancesToUpdate as $instance) {
            if (!$instance->aws_stack_id) {
                continue;
            }

            $details = $this->getStackDetails($instance->aws_stack_id);

            if (!$details) {
                $instance->update(['status' => 'failed', 'message' => 'Tidak dapat mengambil informasi sistem dari AWS.']);
                continue;
            }

            $stackStatus = $details['StackStatus'];
            $statusReason = $details['StackStatusReason'] ?? 'Tanpa keterangan dari AWS (Check AWS Console).';

            switch ($stackStatus) {
                case 'CREATE_COMPLETE':
                    $outputs = collect($details['Outputs'] ?? []);
                    $urlOutput = $outputs->where('OutputKey', 'WebAppURL')->first();
                    $appUrl = $urlOutput ? $urlOutput['OutputValue'] : null;

                    $instance->update([
                        'status' => 'active',
                        'app_url' => $appUrl,
                        'message' => 'Sistem telah berjalan dengan baik.'
                    ]);

                    break;

                case 'CREATE_FAILED':
                case 'ROLLBACK_COMPLETE':
                case 'ROLLBACK_FAILED':
                    $instance->update(['status' => 'failed', 'message' => $statusReason]);

                    break;

                case 'DELETE_COMPLETE':
                    $instance->delete();

                    break;

                case 'DELETE_FAILED':
                     $instance->update(['status' => 'delete_failed', 'message' => $statusReason]);

                     break;

                default:
                    Log::info("Sistem {$instance->id} masih dalam proses dengan status: {$stackStatus}");

                    break;
            }
        }
    }

}
