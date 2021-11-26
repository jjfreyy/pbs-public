<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Paket Sumbangan</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("input/paket_sumbangan/save_paket_sumbangan", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>
            <input class="hidden" name="id_paket_sumbangan" id="id_paket_sumbangan" value="<?php echo $this->session->flashdata("id_paket_sumbangan"); ?>" />
            <div class="name_box">
                <input type="text" class="hidden" name="id_donatur" id="id_donatur" value="<?php echo $this->session->flashdata("id_donatur"); ?>">
                <label for="kode_donatur">Kode Donatur*</label>
                <input type="text" name="kode_donatur" id="kode_donatur" autocomplete="off" list="donatur_list" autofocus required
                value="<?php echo $this->session->flashdata('kode_donatur'); ?>" />
                <datalist id="donatur_list"></datalist>
            </div>
            
            <div class="name_box">
                <label for="nama_donatur">Nama Donatur</label>
                <input type="text" name="nama_donatur" id="nama_donatur" readonly value="<?php echo $this->session->flashdata('nama_donatur'); ?>" />
            </div>

            <div class="name_box">
                <input type="text" class="hidden" name="id_kolektor" id="id_kolektor" value="<?php echo $this->session->flashdata("id_kolektor"); ?>">
                <label for="kode_kolektor">Kode Kolektor*</label>
                <input type="text" name="kode_kolektor" id="kode_kolektor" autocomplete="off" list="kolektor_list" required 
                value="<?php echo $this->session->flashdata("kode_kolektor"); ?>" />
                <datalist id="kolektor_list"></datalist>
            </div>

            <div class="name_box">
                <label for="nama_kolektor">Nama Kolektor</label>
                <input type="text" name="nama_kolektor" id="nama_kolektor" readonly value="<?php echo $this->session->flashdata("nama_kolektor"); ?>" />
            </div>

            <div class="name_box">
                <input type="text" class="hidden" name="id_paket" id="id_paket" value="<?php echo $this->session->flashdata("id_paket"); ?>">
                <label for="kode_paket">Kode Paket*</label>
                <input type="text" name="kode_paket" id="kode_paket" autocomplete="off" list="paket_list" required 
                value="<?php echo $this->session->flashdata('kode_paket'); ?>" />
                <datalist id="paket_list"></datalist>
            </div>

            <div class="name_box">
                <label for="nama_paket">Nama Paket</label>
                <input type="text" name="nama_paket" id="nama_paket" readonly value="<?php echo $this->session->flashdata('nama_paket'); ?>" />
            </div>

            <div class="name_box">
                <label for="nilai_paket">Nilai Paket</label>
                <input type="text" name="nilai_paket" id="nilai_paket" readonly value="<?php echo $this->session->flashdata('nilai_paket'); ?>" />
            </div>

            <div class="name_box">
                <label for="jumlah_paket">Jumlah Paket*</label>
                <input type="text" name="jumlah_paket" id="jumlah_paket" required 
                value="<?php echo is_empty($this->session->flashdata("jumlah_paket")) ? "1" : $this->session->flashdata("jumlah_paket"); ?>">
            </div>

            <div class="description_box">
                <label for="ket_paket">Keterangan</label>
                <textarea name="ket_paket" id="ket_paket"><?php echo $this->session->flashdata('ket_paket'); ?></textarea>
            </div>

            <div class="name_box">
                <label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                <input type="date" name="tgl_jatuh_tempo" id="tgl_jatuh_tempo" value="<?php echo $this->session->flashdata("tgl_jatuh_tempo"); ?>" />
            </div>

            <table class="tb_input" id="tb_input_paket">
                <colgroup>
                    <col span="1" width="35px">
                    <col span="1" width="800px">
                    <col span="1" width="50px">
                </colgroup>

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>
                            <div class="name_box2">
                                <label for="nama_biaowen">Biaowen*</label>
                                <input type="text" id="nama_biaowen" name="nama_biaowen" />
                            </div>
                        </th>
                        <th><a href="#" style="font-weight: bold" id="tambah_biaowen">+</a></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $biaowen = $this->session->flashdata('biaowen');
                    if (!is_empty_array($biaowen)) {
                        $length = count($biaowen);
                        for ($i = 0; $i < $length; $i++) {
                            $nmr = $i + 1;
                            echo "<tr><td>$nmr</td><td><div class='name_box2'><input type='text' name='biaowen[]' 
                            value='$biaowen[$i]' /></div></td><td class='centered'><a href='#' class='hapus_biaowen'>Hapus</a></td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_paket_sumbangan">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "input/paket_sumbangan"); ?>
</html>