<?php

function getReadableFileSize($size): string 
{
  if ($size >= 1048576) {
    
    return (string)(round($size/1048576, 2)) . ' Mb';
  
  } elseif ($size >= 10240 && $size < 1048576) {

    return (string)(round($size/1024, 2)) . ' Kb';
  
  } elseif ($size < 1024) {
 
    return (string)(round($size, 2)) . ' b';
  }
}