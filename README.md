<h1>Category FAQ</h1>

<h2>Installation:</h2>
<strong>Composer:</strong> <br/>

composer require andyworkbase/faq --no-update<br/>
composer update andyworkbase/faq<br/>

<strong>Manually:</strong> <br/>
1) unpack extension package and upload them into Magento root directory/app/code/
2) php bin/magento setup:upgrade
3) php bin/magento setup:di:compile
4) php bin/magento setup:static-content:deploy

<strong>Configuration</strong> - Stores -> Configuration -> MageCloud -> FAQ<br/>
<strong>Manage</strong> - Content -> FAQ

<h2>Features:</h2>
<ul>
<li>support multistore;</li>
<li>support rich snippet schema;</li>
<li>separate FAQ grid (grid inline edit, the ability to add to multiple categories and multiple stores);</li>
<li>ability to add FAQ's directly from the selected category.</li>
</ul>
