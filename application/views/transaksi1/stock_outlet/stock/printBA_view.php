<table>
    <tr>
        <td>Berita Acara Stock Opname</td>
    </tr>
    <tr>
        <td>Inventory Item</td>
    </tr>
</table>
<p>&nbsp;</p>
<table>
    <tr>
        <td colspan="4"><?php echo 'Pada Hari ini '.str_repeat('...',10).' Tanggal '.str_repeat('...',10).' Mulai Jam :';?></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <?php for ($i=0; $i < 7; $i++) { ?>
    <tr>
        <td><?php echo $i+1; ?></td>
        <td><?php echo str_repeat('...',10); ?></td>
        <td><?php echo 'Lokasi : '; ?></td>
        <td><?php echo str_repeat('...',10); ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4"><?php echo 'Telah di lakukan Stock Opname atas Inventory Outlet : '.str_repeat('...',10);?></td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4"><?php echo 'Beralamat di '.str_repeat('...',10);?></td>
    </tr>
</table>
<p>Pemeriksaan dilakukan oleh Staff Perusahaan, yaitu :</p>
<table style="border-collapse:collapse;" width="750" border="1" align="center">
    <tr>
        <td width="20" align="center">No.</td>
        <td width="230" align="center">Pemeriksa</td>
        <td width="230" align="center">Departement</td>
        <td width="230" align="center">Tanda Tangan</td>
    </tr>
    <?php for ($i=0; $i < 10; $i++) { ?>
    <tr>
        <td align="center"><?php echo $i+1; ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php } ?>
</table>
<p>Dan disaksikan oleh Staff kantor Pusat dengan Nama - Nama sebagai berikut :</p>
<table style="border-collapse:collapse;" width="750" border="1" align="center">
<tr>
        <td width="20" align="center">No.</td>
        <td width="230" align="center">Pemeriksa</td>
        <td width="230" align="center">Departement</td>
        <td width="230" align="center">Tanda Tangan</td>
    </tr>
    <?php for ($i=0; $i < 10; $i++) { ?>
    <tr>
        <td align="center"><?php echo $i+1; ?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php } ?>
</table>
<p>Hasil dari Stock Opname ini sepenuhnya akan menjadi Tanggung Jawab dari Outlet ini. (Hasil dari pemeriksaan terlampir.)</p>
<p>Demikian Berita Acara ini dibuat untuk dapat digunakan dengan seperlunya.</p>
<p>&nbsp;</p>
<table width="640" align="center">
  <tr>
    <td width="180">Outlet Manager</td>
    <td width="180">Cost Control</td>
    <td width="180">Operation Head</td>
    <td width="180">Accounting Department</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="180"><?php echo 'Nama : '.str_repeat('...',10); ?></td>
    <td width="180"><?php echo 'Nama : '.str_repeat('...',10); ?></td>
    <td width="180"><?php echo 'Nama : '.str_repeat('...',10); ?></td>
    <td width="180"><?php echo 'Nama : '.str_repeat('...',10); ?></td>
  </tr>
</table>

