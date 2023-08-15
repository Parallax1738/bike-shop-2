import React from "react";
import NavBar from "./NavBar";
import Footer from "./Footer";

const MainPage = () => (
  <div className="App flex flex-col min-h-screen">
    {/* Navbar */}
    <NavBar />

    {/* Cover Picture */}
    <div className="w-full">
      <div className="flex flex-col items-center">
        <a>
          <img
            src="http://placekitten.com/2000/600"
            alt="Cover Image"
            className="w-full object-cover"
          />
        </a>
        <h1 className="text-2xl mt-4 mb-8">Bikes</h1>
      </div>
    </div>

    {/* Cards */}
    <div className="container mx-auto grid grid-cols-4 gap-6 p-4">
      <a href="/" className="card">
        <img
          src="http://placekitten.com/1000/1000"
          alt="Card Image"
          className="w-full h-40 object-cover"
        />
      </a>
      <a href="/" className="card">
        <img
          src="http://placekitten.com/1000/1000"
          alt="Card Image"
          className="w-full h-40 object-cover"
        />
      </a>
      <a href="/" className="card">
        <img
          src="http://placekitten.com/1000/1000"
          alt="Card Image"
          className="w-full h-40 object-cover"
        />
      </a>
      <a href="/" className="card">
        <img
          src="http://placekitten.com/1000/1000"
          alt="Card Image"
          className="w-full h-40 object-cover"
        />
      </a>
    </div>

    {/* Spacer */}
    <div className="flex-grow" />

    {/* Footer */}
    <Footer />
  </div>
);

export default MainPage;
