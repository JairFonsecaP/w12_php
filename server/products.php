<?php

class products
{
    const products = [
        [
            'id' => 0,
            'name' => 'Red Jersey',
            'description' => 'Manchester United Home Jersey, red, sponsored by Chevrolet',
            'price' => 59.99,
            'pic' => 'red_jersey.jpg',
            'qty_in_stock' => 200,
        ],
        [
            'id' => 1,
            'name' => 'White Jersey',
            'description' => 'Manchester United Away Jersey, white, sponsored by Chevrolet',
            'price' => 49.99,
            'pic' => 'white_jersey.jpg',
            'qty_in_stock' => 133,
        ],
        [
            'id' => 2,
            'name' => 'Black Jersey',
            'description' => 'Manchester United Extra Jersey, black, sponsored by Chevrolet',
            'price' => 54.99,
            'pic' => 'black_jersey.jpg',
            'qty_in_stock' => 544,
        ],
        [
            'id' => 3,
            'name' => 'Blue Jacket',
            'description' => 'Blue Jacket for cold and raniy weather',
            'price' => 129.99,
            'pic' => 'blue_jacket.jpg',
            'qty_in_stock' => 14,
        ],
        [
            'id' => 4,
            'name' => 'Snapback Cap',
            'description' => 'Manchester United New Era Snapback Cap- Adult',
            'price' => 24.99,
            'pic' => 'cap.jpg',
            'qty_in_stock' => 655,
        ],
        [
            'id' => 5,
            'name' => 'Champion Flag',
            'description' => 'Manchester United Champions League Flag',
            'price' => 24.99,
            'pic' => 'champion_league_flag.jpg',
            'qty_in_stock' => 321,
        ],
    ];

    function __construct()
    {
    }

    function __destruct()
    {
    }

    public static function productsList()
    {
        $pageData = DEFAULT_PAGE_DATA;

        $pageData['title'] = 'List Product - ' . COMPANY_NAME;
        $output = '<h2>Product list</h2> <table><thead><tr>';
        foreach (self::products[0] as $key => $_) {
            $output .= "<th>$key</th>";
        }
        $output .= '</tr></thead><tbody>';
        foreach (self::products as $product) {
            $output .= "<tr><td>" . $product['id'] . "</td><td>" . $product['name'] . "</td><td>";
            $output .= $product['description'] . "</td><td>" . $product['price'] . "</td><td>" . $product['pic'];
            $output .= "</td><td>" . $product['qty_in_stock'] . '</td>';
        }
        $output .= ' </tbody></table>';
        $pageData['content'] = $output;
        $pageData['viewCounter'] = counterListProducts($pageData);

        webpage::render($pageData);
    }

    public static function productsCataloge()
    {
        $pageData = DEFAULT_PAGE_DATA;
        $pageData['title'] = 'Product Catalog - ' . COMPANY_NAME;

        $output = '';
        foreach (self::products as $product) {
            if ($product['price'] <= 0) {
                continue;
            }
            $output .= '<div class="product"><img src="' . PATH . '/' . $product['pic'] . '" alt="' . $product['description'] . '" title="' . $product['description'] . '">';
            $output .= '<p class="name ">' . $product['name'] . '</p><p class="description">' . $product['description'] . '</p><p class="price">' . $product['price'] . '</p>';
            $output .= '</div>';
        }
        $pageData['content'] = $output;
        $pageData['viewCounter'] = counterCatalogeProducts($pageData);

        webpage::render($pageData);
    }
}
