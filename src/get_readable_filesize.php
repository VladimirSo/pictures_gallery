<?php

function getReadableFileSize($size): string 
{
  $fileSize = '';

  if ($size >= 1048576) {
    
    // return (string)(round($size/1048576, 2)) . ' Mb';
    $fileSize = (string)(round($size/1048576, 2)) . ' Mb';
  
  } elseif ($size >= 10240 && $size < 1048576) {

    // return (string)(round($size/1024, 2)) . ' Kb';
    $fileSize = (string)(round($size/1024, 2)) . ' Kb';
  
  } elseif ($size < 1024) {
 
    // return (string)(round($size, 2)) . ' b';
    $fileSize = (string)(round($size, 2)) . ' b';
  }

  return $fileSize;
}