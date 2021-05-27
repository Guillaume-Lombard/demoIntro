<?php


namespace App\Upload;


use App\Entity\Serie;

class SerieImage
{

    public function save($file, Serie $serie, $directory){

        //rename files
        $newFileName = $serie->getName().'-'.uniqid().'.'.$file->guessExtension();
        //save in to directory (+ modified service.yaml)
        $file->move($directory, $newFileName);
        //save name in BDD
        $serie->setPoster($newFileName);

    }

}