<?php
/**
 * @file ajax_bestellingdetails.php
 */
?>
<?php
echo "<ul>";
foreach($bestellingdetails as $item){
    $product = $item->product;
    echo "<li>$product->naam - $item->aantal</li>";
}
echo "</ul>";