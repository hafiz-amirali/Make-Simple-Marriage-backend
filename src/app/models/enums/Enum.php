<?php

namespace App\Models\Enums;



abstract class Enum { 
  
  // Enumeration constructor 
  final public function __construct($value) { 
      $this->value = $value; 
  } 

  // String representation 
  final public function __toString() { 
      return $this->value; 
  } 
} 
