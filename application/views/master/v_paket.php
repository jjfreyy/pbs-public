<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Paket</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("master/paket/save_paket", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>

            <div class="name_box">
                <input type="text" class="hidden" name="id_paket" id="id_paket" value="<?php echo $this->session->flashdata("id_paket"); ?>">
                <label for="kode_paket">Kode Paket</label>
                <input type="text" name="kode_paket" id="kode_paket" autocomplete="off" list="paket_list" autofocus 
                value="<?php echo $this->session->flashdata('kode_paket'); ?>" />
                <datalist id="paket_list"></datalist>
            </div>
            
            <div class="name_box">
                <label for="nama_paket">Nama Paket*</label>
                <input type="text" name="nama_paket" id="nama_paket" required value="<?php echo $this->session->flashdata('nama_paket'); ?>" />
            </div>

            <div class="name_box hidden">
                <label for="nama_perusahaan">Nama Perusahaan*</label>
                <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="<?php echo $this->session->flashdata('nama_perusahaan'); ?>" />
            </div>

            <div class="name_box">
                <label for="nilai_paket">Nilai Paket</label>
                <input type="text" name="nilai_paket" id="nilai_paket" value="<?php echo $this->session->flashdata('nilai_paket'); ?>" />
            </div>

            <div class="name_box">
                <label for="periode">Periode</label>
                <div class="input_container_box">
                    <input type="text" name="periode" id="periode" value="<?php echo $this->session->flashdata('periode'); ?>" />
                    <div class="select_box">
                        <select name="periode1" id="periode1">
                            <?php 
                            $periode1 = $this->session->flashdata("periode1");
                            $periode1 = is_empty($periode1) ? "T" : $periode1;
                            ?>
                            <option value='H' <?php echo $periode1 === "H" ? "selected" : "" ?>>Hari</option>
                            <option value="B"<?php echo $periode1 === "B" ? "selected" : "" ?>>Bulan</option>
                            <option value="T" <?php echo $periode1 === "T" ? "selected" : "" ?>>Tahun</option>
                        </select>
                    </div>
                </div>
            </div>

            <table class="tb_input">
                <colgroup><col span="1" width="35px"><col span="1" width="800px"><col span="1" width="50px"></colgroup>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>
                            <div class="name_box2">
                                <label for="nama_bank">Rekening</label>
                                <input type="text" id="nama_bank" name="nama_bank" autocomplete="off" list="bank_list" />
                                <datalist id="bank_list"></datalist>
                            </div>
                        </th>
                        <th><a href="#" style="font-weight: bold" id="tambah_bank">+</a></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $bank_list = $this->session->flashdata('bank_list');
                if (!is_empty_array($bank_list) && is_array($bank_list)) {
                    $length = count($bank_list);
                    for ($i = 0; $i < $length; $i++) {
                        $nmr = $i + 1;
                        $bank = explode("|", $bank_list[$i]);
                        $an = $bank[1];
                        $no_rek = $bank[2];
                        echo "<tr><td>$nmr</td>";
                        echo "<td><input type='text' class='hidden' name='bank_list[]' value='$bank_list[$i]'/>";
                        echo "<div class='name_box2'><input type='text' readonly value='$an / $no_rek'/></div></td>";
                        echo "<td class='centered'><a href='#' class='hapus_bank'>Hapus</a></td></tr>";
                    }
                }
                ?>
                </tbody>
            </table>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_paket">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "master/paket"); ?>
</html>