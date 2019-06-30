<h1>Algoritma KNN PHP</h1>
<h4>by : Andre Aditya P</h4>
<h4>A11.2016.10023</h4>


<form class="" action="index.php" method="post">
  <label for="">Masukan Jumlah Dataset</label>
  <input type="number" name="jumlah_data" value="">

  <label for="">Masukan Jumlah Tetangga (K)</label>
  <input type="number" name="k" value="">

  <input type="submit" name="submit" value="Selanjutnya">
</form>

<?php
require_once "conn.php";
if(isset($_POST['submit'])){
  $jumlahdata = $_POST['jumlah_data'];
  $k          = $_POST['k'];

  for ($i=1; $i<=$jumlahdata; $i++) { ?>
    <form class="" action="index.php" method="post">
      <label for="">Masukan Data X ke-<?php echo $i?></label>
      <input type="number" name="datax<?php echo $i?>" value="">
      <label for="">Masukan Data Y ke-<?php echo $i?></label>
      <input type="number" name="datay<?php echo $i?>" value="">
      <label for="">Kategori Data  ke-<?php echo $i?></label>
      <input type="number" name="Kategori<?php echo $i?>" value="">
      <br>
      <input type="hidden" name="jumlah_data" value="<?php echo $jumlahdata ?>">
      <input type="hidden" name="kk" value="<?php echo $k ?>">


<?php } ?>

<h4>Masukan Data Test</h4>
<label for="">Masukan Data Test X</label>
<input type="number" name="datatestx" value="">
<label for="">Masukan Data Test Y</label>
<input type="number" name="datatesty" value=""><br>
<input type="submit" name="submitdata" value="Simpan Data">
</form>
<?php }
if(isset($_POST['submitdata'])){
  $k           = $_POST['kk'];
  $jml         = $_POST['jumlah_data'];?>
  <h1>Data Test</h1>
  <?php   echo $_POST['datatestx']." |";
    echo  $_POST['datatesty']." |<br>"; ?>
    -----------------------------------------<br>
    Data X | Data Y | Jarak <br><br>
<?php
  for ($i=1; $i <= $jml; $i++) {
    //hitung jarak
    $x = $_POST['datax'.$i];
    $y = $_POST['datay'.$i];
    $kat = $_POST['Kategori'.$i];
    $testx = $_POST['datatestx'];
    $testy = $_POST['datatesty'];
    $hitung[$i] = sqrt((pow($x-$testx,2))+(pow($y-$testy,2)));

    echo $x."    |";
    echo $y."    |";
    echo $hitung[$i].'<br>';
    //reset db
    }
    $cek="SELECT id FROM hitung";
    $ea   = mysqli_query($conn,$cek);
    $rowcount=mysqli_num_rows($ea);
    if($rowcount>0){
      $hapus = "delete from hitung";
      $ex_   = mysqli_query($conn,$hapus);
      $hapus = "delete from k";
      $ex_   = mysqli_query($conn,$hapus);
      if($ex_){

        for ($i=1; $i <= $jml; $i++) {
          $x = $_POST['datax'.$i];
          $y = $_POST['datay'.$i];
          $kat = $_POST['Kategori'.$i];
        $sl  = "INSERT INTO hitung (datax, datay, testx , testy , kategori , hitung )VALUES ('$x', '$y', '$testx','$testy','$kat',$hitung[$i])";
        $exp   = mysqli_query($conn,$sl);
        $k  = "INSERT INTO k (k)VALUES ('$k')";
        $exk   = mysqli_query($conn,$k);

      }
      }


    }else{
      for ($i=1; $i <= $jml; $i++) {
        $x = $_POST['datax'.$i];
        $y = $_POST['datay'.$i];
        $kat = $_POST['Kategori'.$i];
      $sql  = "INSERT INTO hitung (datax, datay, testx , testy , kategori , hitung )VALUES ('$x', '$y', '$testx','$testy','$kat',$hitung[$i])";
      $ex   = mysqli_query($conn,$sql);
      $kk  = "INSERT INTO k (k)VALUES ('$k')";
      $exkk   = mysqli_query($conn,$kk);
    }
    }



  echo '<h2>----------Mengurutkan-------------</h3>';
  $q = "SELECT * FROM hitung ORDER BY hitung asc ";
  $query = mysqli_query($conn,$q);?>
<table border="1">
<tr>
  <th>Data X</th>
  <th>Data Y</th>
  <th>Data Test X</th>
  <th>Data Test Y</th>
  <th>Kategori</th>
  <th>Jarak</th>
</tr>
  <?php while($data = mysqli_fetch_array($query)){?>
  <tr>
    <td><?php echo $data['datax']; ?></td>
    <td><?php echo $data['datay']; ?></td>
    <td><?php echo $data['testx']; ?></td>
    <td><?php echo $data['testy']; ?></td>
    <td><?php echo $data['kategori']; ?></td>
    <td><?php echo $data['hitung']; ?></td>
</tr>

<?php }?>
</table>
<h3>------- Menentukan Tetangga Terdekat 'K' --------- </h3>
<?php
$k = "SELECT k FROM k ";
$xk = mysqli_query($conn,$k);
$k = mysqli_fetch_array($xk)['k'];
$q = "SELECT * FROM hitung ORDER BY hitung asc LIMIT $k";
$query = mysqli_query($conn,$q);
$cek="SELECT id FROM kategori";
$ea   = mysqli_query($conn,$cek);
$rowkat=mysqli_num_rows($ea);
if($rowkat>0){
  $hapus = "delete from kategori";
  $ex_   = mysqli_query($conn,$hapus);
}?>
<table border="1">
<tr>
<th>Data X</th>
<th>Data Y</th>
<th>Data Test X</th>
<th>Data Test Y</th>
<th>Kategori</th>
<th>Jarak</th>
</tr>
<?php while($data = mysqli_fetch_array($query)){?>
<tr>
  <td><?php echo $data['datax']; ?></td>
  <td><?php echo $data['datay']; ?></td>
  <td><?php echo $data['testx']; ?></td>
  <td><?php echo $data['testy']; ?></td>
  <td><?php echo $q= $data['kategori']; ?></td>
  <td><?php echo $data['hitung']; ?></td>
</tr>

<?php


$qjum = "INSERT INTO kategori (jumlah) VALUES( $q)";
$exq = mysqli_query($conn,$qjum);
 }?>
</table>
<h3>-------- Penentuan Katagori ----------</h3>

<?php
$sum="SELECT *, SUM(jumlah) FROM kategori  ";
$xs = mysqli_query($conn,$sum);
$row=mysqli_fetch_array($xs)['SUM(jumlah)'];
echo round($row/$rowkat);

}
?>
