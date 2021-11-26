<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Sumbangan</title>
    <?php load_css("base", "style"); ?>
</head>
<body>
    <div class="background"></div>
    <?php $this->load->view("templates/header"); ?>
    <?php $this->load->view("templates/aside"); ?>

    <section id="scol1" class="main">
        <?php echo form_open("input/sumbangan/save_sumbangan", array("class" => "edit_form")); ?>
            <h1>(*) Wajib Diisi</h1>
            <input type="text" class="hidden" name="id_sumbangan" id="id_sumbangan" value="<?php echo $this->session->flashdata("id_sumbangan"); ?>">

            <div class="name_box">
                <label for="no_kwitansi">No. Kwitansi</label>
                <input type="text" name="no_kwitansi" id="no_kwitansi" value="<?php echo $this->session->flashdata("no_kwitansi"); ?>" >
            </div>

            <div class="name_box">
                <input type="text" class="hidden" name="index" id="index" value="<?php echo $this->session->flashdata("index"); ?>">
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
                <label for="nama_penyumbang">Nama Penyumbang*</label>
                <input type="text" name="nama_penyumbang" id="nama_penyumbang" value="<?php echo $this->session->flashdata('nama_penyumbang'); ?>" />
            </div>

            <div class="name_box">
                <label for="tgl_donasi">Tanggal Donasi*</label>
                <input type="date" name="tgl_donasi" id="tgl_donasi" required value="<?php
                $tgl_donasi = $this->session->flashdata("tgl_donasi"); 
                echo is_empty($tgl_donasi) ? date("Y-m-d") : $tgl_donasi; ?>" />
            </div>

            <div class="name_box">
                <input type="text" class="hidden" name="id_paket_sumbangan" id="id_paket_sumbangan" value="<?php echo $this->session->flashdata("id_paket_sumbangan"); ?>" />
                <label for="nama_paket">Paket*</label>
                <input type="text" name="nama_paket" id="nama_paket" autocomplete="off" list="paket_list" required 
                value="<?php echo $this->session->flashdata('nama_paket'); ?>" />
                <datalist name="paket_list" id="paket_list"></datalist>
            </div>

            <div class="name_box">
                <input type="text" class="hidden" name="sisa_nilai_paket1" id="sisa_nilai_paket1" value="<?php echo $this->session->flashdata("sisa_nilai_paket1"); ?>">
                <label for="sisa_nilai_paket">Sisa Nilai Paket</label>
                <input type="text" name="sisa_nilai_paket" id="sisa_nilai_paket" readonly value="<?php echo $this->session->flashdata("sisa_nilai_paket"); ?>" />
            </div>

            <div class="name_box">
                <label for="jumlah_donasi">Jumlah donasi*</label>
                <input type="text" name="jumlah_donasi" id="jumlah_donasi" required 
                value="<?php echo $this->session->flashdata('jumlah_donasi'); ?>" />
            </div>

            <div class="select_box">
                <label for="metode_pembayaran">Via</label>
                <select name="metode_pembayaran" id="metode_pembayaran">
                    <option value="Tunai" <?php 
                    $metode_pembayaran = $this->session->flashdata("metode_pembayaran");
                    echo $metode_pembayaran === "Tunai" ? "selected" : ""; ?>>Tunai</option>
                    <option value="Transfer" <?php echo $metode_pembayaran === "Transfer" ? "selected" : ""; ?>>Transfer</option>
                </select>
            </div>

            <aside class="search_container3">
                <div class="accordion"><span class="nav_icon1"></span></div>
                <div class="search_box2">
                    <div class="name_box">
                        <input class="hidden" name="id_bank" id="id_bank" value="<?php echo $this->session->flashdata("id_bank"); ?>"></input>
                        <label for="bank">Ke Rekening</label>
                        <input type="text" id="bank" name="bank" autocomplete="off" required value="<?php echo $this->session->flashdata("bank"); ?>" 
                        list="bank_list">
                        <datalist id="bank_list"></datalist>
                    </div>

                    <div class="name_box">
                        <label for="rek_pengirim">Dari Rekening</label>
                        <input type="text" name="rek_pengirim" id="rek_pengirim" autocomplete="off" 
                        value="<?php echo $this->session->flashdata("rek_pengirim"); ?>" />
                    </div>
                </div>
            </aside>

            <div class="description_box">
                <label for="ket_sumbangan">Keterangan</label>
                <textarea name="ket_sumbangan" id="ket_sumbangan"><?php echo $this->session->flashdata('ket_sumbangan'); ?></textarea>
            </div>

            <table class="tb_input">
                <colgroup><col span="1" width="35px"><col span="1" width="35px"><col span="1" width="800px"><col span="1" width="50px"></colgroup>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Lunas</th>
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
                        $biaowen_detail = explode("|", $biaowen[$i]);
                        $checked = $biaowen_detail[0] == 0 ? "" : "checked";
                        echo "<tr><td>$nmr</td><td class='centered'><input type='text' class='hidden' name='biaowen[]' value='$biaowen[$i]'/>
                        <input type='checkbox' $checked/></td>
                        <td><div class='name_box2'><input type='text' readonly value='$biaowen_detail[1]' /></div></td>
                        <td class='centered'><a href='#' class='hapus_biaowen'>Hapus</a></td></tr>";
                    }
                }
                ?>
                </tbody>
            </table>

            <div class="button">
                <?php echo $this->session->flashdata("report"); ?>
                <button class="i_btn" id="save_btn" name="save_sumbangan">Simpan</button>
                <button class="i_btn" id="reset_btn" type="reset">Reset</button>
            </div>
        </form>
    </section>
</body>
<?php load_js("script", "nav", "global", "input/sumbangan"); ?>
</html>