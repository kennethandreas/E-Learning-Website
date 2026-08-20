<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AiKeywordSeeder extends Seeder
{
    public function run()
    {
        DB::table('ai_keywords')->insert([

            // ================= IPA =================
            [
                'keyword' => 'ipa',
                'response' => json_encode([
                    'definition' => 'IPA adalah pelajaran yang mempelajari alam dan gejala-gejala alam di sekitar kita.',
                    'materials' => [
                        [
                            'title' => 'Makhluk Hidup',
                            'answer' => 'Makhluk hidup adalah makhluk yang bernapas, tumbuh, bergerak, dan berkembang biak.'
                        ],
                        [
                            'title' => 'Benda Mati',
                            'answer' => 'Benda mati adalah benda yang tidak memiliki ciri-ciri makhluk hidup.'
                        ],
                        [
                            'title' => 'Gaya dan Gerak',
                            'answer' => 'Gaya adalah tarikan atau dorongan yang dapat menyebabkan benda bergerak.'
                        ],
                        [
                            'title' => 'Sumber Energi',
                            'answer' => 'Sumber energi adalah segala sesuatu yang dapat menghasilkan energi.'
                        ],
                    ]
                ])
            ],

            // ================= MATEMATIKA =================
            [
                'keyword' => 'matematika',
                'response' => json_encode([
                    'definition' => 'Matematika adalah ilmu yang mempelajari angka, bentuk, pola, dan cara berhitung.',
                    'materials' => [
                        [
                            'title' => 'Penjumlahan',
                            'answer' => 'Penjumlahan adalah operasi untuk menjumlahkan dua atau lebih bilangan.'
                        ],
                        [
                            'title' => 'Pengurangan',
                            'answer' => 'Pengurangan adalah operasi untuk mengurangi suatu bilangan dengan bilangan lainnya.'
                        ],
                        [
                            'title' => 'Perkalian',
                            'answer' => 'Perkalian adalah penjumlahan berulang dari suatu bilangan.'
                        ],
                        [
                            'title' => 'Pembagian',
                            'answer' => 'Pembagian adalah operasi untuk membagi suatu bilangan menjadi beberapa bagian sama besar.'
                        ],
                    ]
                ])
            ],

            // ================= BAHASA INDONESIA =================
            [
                'keyword' => 'bahasa indonesia',
                'response' => json_encode([
                    'definition' => 'Bahasa Indonesia adalah bahasa resmi dan bahasa persatuan bangsa Indonesia.',
                    'materials' => [
                        [
                            'title' => 'Kata Baku',
                            'answer' => 'Kata baku adalah kata yang sesuai dengan kaidah bahasa Indonesia.'
                        ],
                        [
                            'title' => 'Teks Narasi',
                            'answer' => 'Teks narasi adalah teks yang menceritakan suatu peristiwa secara berurutan.'
                        ],
                        [
                            'title' => 'Teks Deskripsi',
                            'answer' => 'Teks deskripsi adalah teks yang menggambarkan suatu objek secara jelas.'
                        ],
                        [
                            'title' => 'Puisi Anak',
                            'answer' => 'Puisi anak adalah karya sastra yang menggunakan bahasa sederhana dan mudah dipahami.'
                        ],
                    ]
                ])
            ],

            // ================= IPS =================
            [
                'keyword' => 'ips',
                'response' => json_encode([
                    'definition' => 'IPS adalah pelajaran yang mempelajari kehidupan sosial dan lingkungan masyarakat.',
                    'materials' => [
                        [
                            'title' => 'Lingkungan Sekitar',
                            'answer' => 'Lingkungan sekitar adalah tempat makhluk hidup tinggal dan berinteraksi.'
                        ],
                        [
                            'title' => 'Kegiatan Ekonomi',
                            'answer' => 'Kegiatan ekonomi meliputi kegiatan produksi, distribusi, dan konsumsi.'
                        ],
                        [
                            'title' => 'Keragaman Budaya',
                            'answer' => 'Keragaman budaya adalah perbedaan adat, bahasa, dan kebiasaan dalam masyarakat.'
                        ],
                    ]
                ])
            ],

            // ================= PPKn =================
            [
                'keyword' => 'ppkn',
                'response' => json_encode([
                    'definition' => 'PPKn adalah pelajaran yang membahas nilai Pancasila dan kewarganegaraan.',
                    'materials' => [
                        [
                            'title' => 'Pancasila',
                            'answer' => 'Pancasila adalah dasar negara dan pedoman hidup bangsa Indonesia.'
                        ],
                        [
                            'title' => 'Hak dan Kewajiban',
                            'answer' => 'Hak adalah sesuatu yang harus diterima, kewajiban adalah sesuatu yang harus dilakukan.'
                        ],
                        [
                            'title' => 'Aturan di Sekolah',
                            'answer' => 'Aturan di sekolah dibuat agar kegiatan belajar berjalan tertib dan aman.'
                        ],
                    ]
                ])
            ],

        ]);
    }
}
