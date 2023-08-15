import React from "react";

const NavBar = () => (
  <div className="bg-black">
    <div className="container mx-auto flex justify-between items-center p-4">
      <div className="text-white text-4xl font-bold">Bike Shop</div>
      <div>
        <a href="/booking" className="text-white px-4">
          BOOKING
        </a>
        <a href="/auth" className="text-white px-4">
          LOG IN
        </a>
        <a href="/cart" className="text-white px-4">
          CART
        </a>
      </div>
    </div>
  </div>
);

export default NavBar;
