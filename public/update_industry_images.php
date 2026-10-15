<?php
$conn = mysqli_connect("localhost", "root", "", "itech_db");
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

$updates = [
    'hotels-resorts' => 'sol_hospitality.png',
    'healthcare' => 'sol_healthcare.png',
    'retail-chains' => 'sol_retail.png',
    'manufacturing' => 'sol_manufacturing.png',
    'warehousing-logistics' => 'sol_warehouse.png',
    'banking-finance' => 'hero_enterprise.png',
    'parking-vehicle-management' => 'serv_surveillance.png',
    'construction-camps' => 'serv_cabling.png',
    'oil-gas' => 'noc_center.png'
];

foreach ($updates as $slug => $img) {
    mysqli_query($conn, "UPDATE solutions SET image = '$img' WHERE slug = '$slug'");
}

mysqli_close($conn);
echo "Successfully updated mapping for 9 industries.";
?>
