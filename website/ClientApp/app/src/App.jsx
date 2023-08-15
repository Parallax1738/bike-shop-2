import React from "react";
import "./index.css";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import MainPage from "../components/MainPage";
import BookingPage from "../components/BookingPage";
import NotFound from "../components/NotFound";

const App = () => {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/booking" element={<BookingPage />} />
        <Route path="/" element={<MainPage />} />
        <Route path="*" element={<NotFound />} />
      </Routes>
    </BrowserRouter>
  );
};

export default App;
