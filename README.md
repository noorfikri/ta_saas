# PEMBUATAN APLIKASI SOFTWARE AS A SERVICE UNTUK SISTEM INFORMASI MANAJEMEN JUAL BELI

## Demo

Untuk demonstrasi aplikasi dapat dilihat pada link dibawah ini:
https://dasboardtenanttokoku.my.id/

## Pendahuluan

Aplikasi ini adalah apilkasi hasil dari tugas akhir yang berjudul "Pembuatan Aplikasi Software as a service Untuk Sistem Informasi Manajemen Jual Beli.

Aplikasi ini dibuat dengan tujuan untuk menyelesaikan permasalahan pemilik bisnis dimana manajemen bisnisnya menjadi lebih kompleks saat bisnsi mereka berkembang dan mereka membuat cabang baru.

## Desain Sistem

Sistem akan dibagi menjadi 2 yaitu sistem manajemen dashboard tenant dan juga sistem informasi bisnis milik tenant. Pada sistem manajemen dashboard tenant, pemilik tenant dapat membuat sistem informasi bisnis sesuai dengan kebutuhan pemilik bisnis. Sistem dashboard melakukan ini dengan menggunakan konsep cloud computing software-as-a-service. 
	
Dengan menggunakan konsep cloud computing software-as-a-service, sistem akan dapat langsung dibuat tanpa pengguna perlu mengetahui seluk beluk teknis yang memberatkan dalam membuat sistem informasi mereka. Sistem dashboard akan langsung mengirimkan permintaan kepada layanan cloud AWS CloudFormation saat pengguna ingin membuat sistem baru. AWS CloudFormation akan memberikan dan mempersiapkan layanan yang diperlukan untuk menjalankan sistem informasi bisnis. Saat layanan itu siap, sistem dashboard akan menampilkan tautan yang pemilik bisnis bisa langsung digunakan untuk langsung membuka sistem informasi bisnis yang baru dibuat dan pengguna dapat langsung menggunakannya.

Sistem dashboard tenant menggunakan arsitektur model silo. Saat pengguna membuat sistem baru melalui sistem dashboard tenant, baik instance EC2 dan instance RDS dan juga keperluan sumber daya jaringan lainnya untuk sistem informasi tenant akan dibuatkan baru oleh AWS CloudFormation secara terpisah dengan pengguna lainnya. Secara umum, terdapat 5 bagian utama dari arsitektur sistem baru yang dibuat pada AWS yaitu VPC, dan 2 Subnet dan 2 Instance. Seluruh sumber daya sistem yang dibuat akan berjalan pada VPC tersebut. Di Dalam arsitektur sistem yang terbuat, terdapat 2 subnet yaitu public subnet dan private subnet. Public subnet dapat diakses secara publik, sedangkan private subnet hanya bisa diakses oleh sumber daya yang berada pada public subnet tersebut. Seluruh subnet ini sudah memiliki security group sendiri. Security group ini adalah sebuah aturan pengelola lalu lintas jaringan yang akan melakukan filtering untuk lalu lintas apa saja yang dapat masuk ke subnet tersebut, seperti hanya memperbolehkan lalu lintas MySQL untuk private subnet dan memperbolehkan seluruh lalu lintas HTTP publik pada public subnet. Untuk menghubungkan sumber daya dari public subnet ke internet, dibuatkan sebuah Internet Gateway dan dipasangkan ke dalam VPC ini. Terdapat 2 instance yang berjalan pada jaringan virtual ini yaitu instance EC2 dan instance RDS. Instance EC2 akan menjalankan aplikasi utama sistem informasi bisnis pengguna. Instance EC2 ini terletak pada public subnet dan dapat diakses oleh pengguna dan pengunjung web secara publik. Instance RDS akan menjalankan layanan basis data MySQL sistem. Instance RDS ini berada di bagian private subnet dan tidak dapat diakses secara umum, hanya aplikasi yang berjalan pada public subnet mengakses instance ini. Desain arsitektur sistem yang dibuat ini dapat dilihat pada gambar dibawah ini

<img width="588" height="466" alt="diagram-export-03-11-2025-15 15 05" src="https://github.com/user-attachments/assets/218866a5-df6c-458c-acc7-408cc774653b" />

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

AdminLTE is an open source project that is licensed under the MIT license.
