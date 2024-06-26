<?php

use GuzzleHttp\Client;

defined('BASEPATH') or exit('No direct script access allowed');
class Download extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
        $this->load->model('M_invoice');
    }


    public function index()
    {
        $data = [
            'title' => 'Download',
            'invoice' => $this->db->query("SELECT a.no_nota
            FROM tb_invoice as a 
            where a.import = 'T' 
            ")->row(),
            'stok' => $this->db->query("SELECT a.kode_stok_produk
            FROM tb_stok_produk as a 
            where a.import = 'T' 
            ")->row(),
            'pembelian' => $this->db->query("SELECT a.no_nota
            FROM tb_pembelian as a 
            where a.import = 'T' 
            ")->row(),

        ];
        $this->load->view('download/index', $data);
    }

    public function download_menu()
    {
        // Inisialisasi objek client GuzzleHttp
        $client = new Client();

        // URL API
        $url = 'https://crepeapi.ptagafood.com/api/tb_menu_crepe';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('tb_servis');
                $this->db->truncate('tb_harga');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                foreach ($data['menu'] as $item) {
                    $data2 = [
                        'id_servis' => $item['id_servis'],
                        'nm_servis' => $item['nm_servis'],
                        'id_kategori' => $item['id_kategori'],
                        'durasi' => $item['durasi'],
                        'menit' => $item['menit'],
                        'biaya' => $item['biaya'],
                        'komisi' => $item['komisi'],
                        'foto' => $item['foto'],
                    ];
                    $this->db->insert('tb_servis', $data2);
                }
                foreach ($data['harga'] as $item) {
                    $data2 = [
                        'id_harga' => $item['id_harga'],
                        'id_servis' => $item['id_servis'],
                        'distirbusi' => $item['distirbusi'],
                        'harga' => $item['harga'],
                    ];
                    $this->db->insert('tb_harga', $data2);
                }
                redirect('download');
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }
    public function download_bahan()
    {
        // Inisialisasi objek client GuzzleHttp
        $client = new Client();

        // URL API
        $url = 'https://crepeapi.ptagafood.com/api/tb_bahan_crepe';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('tb_produk');
                $this->db->truncate('tb_satuan');
                $this->db->truncate('harga_toping');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['bahan']) && is_array($data['bahan'])) {
                    // Loop setiap elemen dalam array 'bahan'
                    
                    foreach ($data['bahan'] as $item) {
                        $data2 = [
                            'id_produk' => $item['id_produk'],
                            'id_kategori' => $item['id_kategori'],
                            'id_satuan' => $item['id_satuan'],
                            'sku' => $item['sku'],
                            'nm_produk' => $item['nm_produk'],
                            'harga_modal' => $item['harga_modal'],
                            'harga' => $item['harga'],
                            'stok' => $item['stok'],
                            'terjual' => $item['terjual'],
                            'foto' => $item['foto'],
                            'diskon' => $item['diskon'],
                            'komisi' => $item['komisi'],
                            'monitoring' => $item['monitoring'],
                            'qty_toping' => $item['qty_toping'],
                        ];
                        $this->db->insert('tb_produk', $data2);
                    }

                    foreach ($data['harga_toping'] as $ht) {
                        $data3 = [
                            'id_harga_toping' => $ht['id_harga_toping'],
                            'id_produk' => $ht['id_produk'],
                            'id_distibusi' => $ht['id_distibusi'],
                            'harga' => $ht['harga'],
                        ];
                        $this->db->insert('harga_toping', $data3);
                    }

                    foreach ($data['tb_satuan'] as $ht) {
                        $data3 = [
                            'id_satuan' => $ht['id_satuan'],
                            'satuan' => $ht['satuan'],
                        ];
                        $this->db->insert('tb_satuan', $data3);
                    }
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }

    public function download_resep()
    {
        $client = new Client();
        $url = 'https://crepeapi.ptagafood.com/api/tb_resep_crepe';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('tb_resep');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['resep']) && is_array($data['resep'])) {
                    // Loop setiap elemen dalam array 'resep'
                    foreach ($data['resep'] as $item) {
                        $data2 = [
                            'id_resep' => $item['id_resep'],
                            'id_servis' => $item['id_servis'],
                            'id_produk' => $item['id_produk'],
                            'takaran' => $item['takaran'],
                        ];
                        $this->db->insert('tb_resep', $data2);
                    }
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }
    public function download_stok_opname()
    {
        $client = new Client();
        $url = 'https://crepeapi.ptagafood.com/api/tb_stok_opname';

        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                if (isset($data['stok']) && is_array($data['stok'])) {

                    foreach ($data['stok'] as $item) {
                        $this->db->where('id_produk', $item['id_produk']);
                        $this->db->update('tb_stok_produk', ['opname' => 'Y']);
                        $data2 = [
                            'id_stok_produk' => $item['id_stok_produk'],
                            'kode_stok_produk' => $item['kode_stok_produk'],
                            'id_produk' => $item['id_produk'],
                            'stok_program' => $item['stok_program'],
                            'debit' => $item['debit'],
                            'kredit' => $item['kredit'],
                            'tgl' => $item['tgl'],
                            'tgl_input' => $item['tgl_input'],
                            'admin' => $item['admin'],
                            'jenis' => $item['jenis'],
                            'ttl_stok' => $item['ttl_stok'],
                            'harga' => $item['harga'],
                            'status' => $item['status'],
                            'ket' => $item['ket'],
                            'opname' => $item['opname'],
                            'import' => 'Y',
                        ];
                        $this->db->insert('tb_stok_produk', $data2);
                    }

                    $data1 = [];
                    foreach ($data['stok'] as $item) {
                        $data1[] = [
                            'id_stok_produk' => $item['id_stok_produk'],
                        ];
                    }
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', 'https://crepeapi.ptagafood.com/api/stok_update_tarik', [
                        'form_params' => $data1
                    ]);

                    // Mendapatkan respons dari server
                    $responseBody = $response->getBody()->getContents();
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function download_stok_masuk()
    {
        $client = new Client();
        $url = 'https://crepeapi.ptagafood.com/api/tb_stok_masuk';

        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                if (isset($data['stok']) && is_array($data['stok'])) {
                    foreach ($data['stok'] as $item) {
                        $data2 = [
                            'id_stok_produk' => $item['id_stok_produk'],
                            'kode_stok_produk' => $item['kode_stok_produk'],
                            'id_produk' => $item['id_produk'],
                            'stok_program' => $item['stok_program'],
                            'debit' => $item['debit'],
                            'kredit' => $item['kredit'],
                            'tgl' => $item['tgl'],
                            'tgl_input' => $item['tgl_input'],
                            'admin' => $item['admin'],
                            'jenis' => $item['jenis'],
                            'ttl_stok' => $item['ttl_stok'],
                            'harga' => $item['harga'],
                            'status' => $item['status'],
                            'ket' => $item['ket'],
                            'opname' => $item['opname'],
                            'import' => 'Y',
                        ];
                        $this->db->insert('tb_stok_produk', $data2);
                    }

                    $data1 = [];
                    foreach ($data['stok'] as $item) {
                        $data1[] = [
                            'id_stok_produk' => $item['id_stok_produk'],
                        ];
                    }
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('POST', 'https://crepeapi.ptagafood.com/api/stok_update_tarik', [
                        'form_params' => $data1
                    ]);

                    // Mendapatkan respons dari server
                    $responseBody = $response->getBody()->getContents();
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function download_voucher_void()
    {
        $client = new Client();
        $url = 'https://crepeapi.ptagafood.com/api/voucher_void';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('voucher_void');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['voucher']) && is_array($data['voucher'])) {
                    // Loop setiap elemen dalam array 'resep'
                    foreach ($data['voucher'] as $item) {
                        $data2 = [
                            'id_voucher' => $item['id_voucher'],
                            'voucher' => $item['voucher'],
                            'terpakai' => $item['terpakai']
                        ];
                        $this->db->insert('voucher_void', $data2);
                    }

               
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }

        $client = new Client();
        $url = 'https://crepeapi.ptagafood.com/api/voucher_invoice';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {

                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['voucher_invoice']) && is_array($data['voucher_invoice'])) {
                    // Loop setiap elemen dalam array 'resep'
                    foreach ($data['voucher_invoice'] as $item) {
                        $voucher = $this->db->get_where('tb_voucher_invoice', ['id_voucher' => $item['id_voucher']])->row();
                        if (!empty($voucher->id_voucher)) {
                            # code...
                        } else {
                            $data2 = [
                                'id_voucher' => $item['id_voucher'],
                                'no_voucher' => $item['no_voucher'],
                                'jenis' => $item['jenis'],
                                'jumlah' => $item['jumlah'],
                                'tgl_input' => $item['tgl_input'],
                                'tgl_akhir' => $item['tgl_akhir'],
                                'tgl_pakai' => $item['tgl_pakai'],
                                'admin' => $item['admin'],
                                'ket' => $item['ket'],
                                'status' => $item['status'],
                            ];
                            $this->db->insert('tb_voucher_invoice', $data2);
                        }
                    }
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }

    public function download_user()
    {
        // Inisialisasi objek client GuzzleHttp
        $client = new Client();

        // URL API
        $url = 'https://crepeapi.ptagafood.com/api/tb_user_crepe';

        try {
            // Mengirimkan permintaan GET ke API
            $response = $client->request('GET', $url);

            // Mendapatkan kode status respons
            $statusCode = $response->getStatusCode();

            // Jika respons sukses (kode status 200)
            if ($statusCode == 200) {
                $this->db->truncate('tb_user');
                $this->db->truncate('tb_role');
                $this->db->truncate('tb_permission');
                // Mendapatkan konten respons dalam bentuk string
                $body = $response->getBody()->getContents();

                // Mengubah konten respons menjadi array
                $data = json_decode($body, true);
                if (isset($data['tb_user']) && is_array($data['tb_user'])) {
                    // Loop setiap elemen dalam array 'tb_user'
                    
                    foreach ($data['tb_user'] as $item) {
                        $data2 = [
                            'kd_user' => $item['kd_user'],
                            'nm_user' => $item['nm_user'],
                            'username' => $item['username'],
                            'password' => $item['password'],
                            'email' => $item['email'],
                            'id_role' => $item['id_role'],
                            'aktif' => $item['aktif'],
                            'tgl_masuk' => $item['tgl_masuk'],
                            'warna' => $item['warna'],
                        ];
                        $this->db->insert('tb_user', $data2);
                    }
                    foreach ($data['tb_role'] as $item) {
                        $data2 = [
                            'id_role' => $item['id_role'],
                            'nm_role' => $item['nm_role'],
                            'input' => $item['input'],
                            'edit_hapus' => $item['edit_hapus'],
                            'i_grade' => $item['i_grade'],
                            'e_grade' => $item['e_grade'],
                            'gudang' => $item['gudang'],
                        ];
                        $this->db->insert('tb_role', $data2);
                    }
                    foreach ($data['tb_permission'] as $item) {
                        $data2 = [
                            'id_user' => $item['id_user'],
                            'permission' => $item['permission'],
                        ];
                        $this->db->insert('tb_permission', $data2);
                    }

                    
                    redirect('download');
                } else {
                    echo "Data menu tidak tersedia.";
                }
            } else {
                echo "Gagal mengambil data dari API. Kode status: " . $statusCode;
            }
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            echo "Error: " . $e->getMessage();
        }
    }
}
