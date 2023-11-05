<div class="py-10">
    <div class="container px-5 mx-auto mb-12 flex justify-between items-center">
        <div class="text-3xl font-bold mt-6 mb-6">EXPLORE OUR PRODUCTS</div>
        <div>
            <?php
            require_once __DIR__ . '/../ui-components/button.php';
            button(['text' => 'BOOK NOW', 'targetPage' => 'booking.php']);
            ?>
        </div>
    </div>

    <div class="container mx-auto grid grid-cols-3 gap-6 mb-6 px-5">
        <div class="card">
            <div><img src="https://placekitten.com/600/460" alt="Helmets"></div>
            <div class="card-title">Helmets</div>
            <div><a href="#" class="card-btn">View Products</a></div>
        </div>

        <div class="card">
            <div><img src="https://placekitten.com/601/460" alt="Scooters"></div>
            <div class="card-title">Scooters</div>
            <div><a href="#" class="card-btn">View Products</a></div>
        </div>

        <div class="card">
            <div><img src="https://placekitten.com/620/470" alt="Mountain Bikes"></div>
            <div class="card-title">Mountain Bikes</div>
            <div><a href="#" class="card-btn">View Products</a></div>
        </div>
    </div>

    <div class="container mx-auto grid grid-cols-2 gap-6 px-5">
        <div class="card">
            <div><img src="https://placekitten.com/609/460" alt="Men"></div>
            <div class="card-title">Men</div>
            <div><a href="#" class="card-btn">View Products</a></div>
        </div>

        <div class="card">
            <div><img src="https://placekitten.com/605/460" alt="Women"></div>
            <div class="card-title">Women</div>
            <div><a href="#" class="card-btn">View Products</a></div>
        </div>
    </div>
</div>