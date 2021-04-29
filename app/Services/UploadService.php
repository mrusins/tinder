<?php
namespace App\Services;


use App\Repositories\DBInterface;
use App\Repositories\MySQLRepository;

class UploadService
{

    private DBInterface $tinderDB;

    public function __construct(DBInterface $tinderDB){

        $this->tinderDB = $tinderDB;
    }
    public function upload(array $post){
        if (isset($post['upload'])){
            $file = $_FILES['file'];

            $fileName = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name'];
            $fileSize = $_FILES['file']['size'];
            $fileError = $_FILES['file']['error'];
            $fileType = $_FILES['file']['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = ['jpg', 'jpeg', 'png'];

            if (in_array($fileActualExt, $allowed)){

                if ($fileError === 0) {
                    if ($fileSize < 5000000) {
                        $_SESSION['error'] = true;
                        $fileNameNew = uniqid('', false).".".$fileActualExt;
                        $this->tinderDB->updatePic(2, $fileNameNew); //TODO user ID from _SESSION
                        $fileDestination = '../public/pictures/'.$fileNameNew;
                        move_uploaded_file($fileTmpName, $fileDestination);

                    } else {
                        $_SESSION['error'] = false;

                    }
                } else {
                    echo 'There was an error uploading your file!';
                }
            }

        }
    }

}
