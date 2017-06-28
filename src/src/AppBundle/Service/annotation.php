<?php

use Doctrine\Common\Annotations\AnnotationReader;
use AppBundle\Entity\Category;

$annotationReader = new AnnotationReader();

//Get class annotation
$reflectionClass = new ReflectionClass('Category');
$classAnnotations = $annotationReader->getClassAnnotations($reflectionClass);

echo "========= CLASS ANNOTATIONS =========" . PHP_EOL;
var_dump($classAnnotations);

// You can also pass ReflectionObject to the same method to read annotations in runtime
$annotationDemoObject = new Category();
$reflectionObject = new ReflectionObject($annotationDemoObject);

$objectAnnotations = $annotationReader->getClassAnnotations($reflectionObject);

echo "========= OBJECT ANNOTATIONS =========" . PHP_EOL;
var_dump($objectAnnotations);


//Property Annotations
$reflectionProperty = new ReflectionProperty('Category', 'property');
$propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);

echo "=========   PROPERTY ANNOTATIONS =========" . PHP_EOL;
var_dump($propertyAnnotations);


// Method Annotations
$reflectionMethod = new ReflectionMethod('Category', 'getProperty');
$methodAnnotations = $annotationReader->getMethodAnnotations($reflectionMethod);


echo "=========   Method ANNOTATIONS =========" . PHP_EOL;
var_dump($propertyAnnotations);