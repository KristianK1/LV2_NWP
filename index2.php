<br><br>
 
 
<form action="" method="post" enctype="multipart/form-data">
    <label for="fileUpload">Odaberite datoteku</label>
    <input type="hidden" name="fileUploadFlag" value="1" />
    <input type="file" id="fileUpload" name="fileUpload" required> <br><br>
    <input type="submit">
</form>
 
<?php
 
 
$key  = hash('sha256', 'secret_key');
$cipher = 'AES-256-CBC';
$iv_length = openssl_cipher_iv_length($cipher);
$options = 0; 
$iv = random_bytes($iv_length); 
 
 
if(isset($_POST['fileUploadFlag']) && $_POST['fileUploadFlag'] == 1) {
 
 
    $extension = pathinfo($_FILES['fileUpload']['name'], PATHINFO_EXTENSION);

    $encrypted_file = 'crypted/' . $_FILES['fileUpload']['name'] . '.enc';
    $temp_file = $_FILES['fileUpload']['tmp_name'];

    $input_file = fopen($temp_file, 'r');
    $output_file = fopen($encrypted_file, 'w');

    $plain = fread($input_file, filesize($temp_file));
    $encrypt = base64_encode(openssl_encrypt($plain, $cipher, $key, 0, $iv)); 
    fwrite($output_file, $iv);
    fwrite($output_file, $encrypt);

    fclose($input_file);
    fclose($output_file);
}
 
$encrypted_files_dir = 'crypted/';
 
    $encrypted_files = glob($encrypted_files_dir . '*.enc');
 
    foreach ($encrypted_files as $encrypted_file) {
 
        $decrypted_file = str_replace('.enc', '', $encrypted_file);
 
        $input_file = fopen($encrypted_file, 'r');
        $output_file = fopen($decrypted_file, 'w');
 
        $iv = fread($input_file, $iv_length);
        $encrypt = fread($input_file, filesize($encrypted_file));
        $decrypt = openssl_decrypt(base64_decode($encrypt), $cipher, $key, 0, $iv);
        fwrite($output_file, $decrypt);
 
 
        fclose($input_file);
        fclose($output_file);
 
        echo '<br><a href="'.$decrypted_file.'">Link '.basename($decrypted_file).'</a>';
    }
 
 
 
 
 
 
?>