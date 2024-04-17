import React from "react";
import ReactDOM from "react-dom/client";

import "./index.css";
import "./Cs/book.css";
import App from "./App";
import { store } from "./component/Redux/store";
import { Provider, useDispatch } from "react-redux";
import { QueryClient } from "react-query";
import axios from "axios";

const queryClient = new QueryClient();
axios.defaults.headers.common["Content-Type"] = "application/json";
axios.defaults.headers.common["allowed_origins"] = "*";
axios.defaults.baseURL = "http://localhost:8000/";

const BookList = () => {
  return (
    <>
      <Provider store={store}>
        <App />
      </Provider>
    </>
  );
};

const root = ReactDOM.createRoot(document.getElementById("root"));

root.render(<BookList />);
