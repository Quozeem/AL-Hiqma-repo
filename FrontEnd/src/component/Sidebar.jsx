import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { useState, useEffect } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import { setbook, setdataNotAccount } from "./Redux/Index";
import axios from "axios";

const Sidebar = () => {
  const Navigate = useNavigate();
  const dispatch = useDispatch();
  const [dropDown, setdropDown] = useState(false);
  const [dropDownsettng, setdropDownsettng] = useState(false);

  const { dataNotAccount, Category, book } = useSelector(
    (state) => state.Index
  );
  function Dashboardpage() {
    Navigate("/Admin/Dashboard", { replace: true });
  }
  /*******  Book LIST *****/
  useEffect(() => {
    async function BookList() {
      axios
        .get("api/v1/books")
        .then((res) => {
          dispatch(setdataNotAccount("Table is Currently Empty"));
          dispatch(setbook(res.data.data));
         
        })
        .catch((error) => {});
    }
    BookList();
  }, []);
  return (
    <div>
      <div className="collapsible" style={{ width: "20%" }}>
        <nav>
          <div className="admin-side">
            {/* <img src={logo} width="200" alt="" /> */}
            {/* <p onclick="closenav()"><i class="fa-solid fa-times"></i></p> */}
          </div>
          <div className="admin">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="36"
              height="35"
              viewBox="0 0 36 35"
              fill="none"
            >
              <path
                d="M29.3573 19.0902H19.2719L25.9886 25.8069C26.2453 26.0636 26.6678 26.0844 26.9317 25.8358C28.5765 24.2861 29.708 22.1971 30.0399 19.8488C30.0969 19.4468 29.7632 19.0902 29.3573 19.0902ZM28.6845 16.336C28.3343 11.2587 24.277 7.20137 19.1996 6.85115C18.812 6.82437 18.4856 7.15121 18.4856 7.53969V17.0501H27.9964C28.3849 17.0501 28.7113 16.7236 28.6845 16.336ZM16.4455 19.0902V9.00475C16.4455 8.59885 16.0889 8.26521 15.6872 8.32216C10.6222 9.0379 6.75064 13.4628 6.93085 18.7659C7.11616 24.2122 11.8055 28.6783 17.2543 28.6099C19.3964 28.5831 21.3758 27.8929 23.0032 26.7385C23.339 26.5005 23.3611 26.0062 23.0699 25.715L16.4455 19.0902Z"
                fill="black"
              />
            </svg>
            <span id="list-items">Books Dashboard</span>
          </div>
          <ul id="list">
            <li className="submenu" id="submenu">
              <a href="#">
                <span id="list-items">Book Store</span>
                {/* <RiArrowDropDownLine className="mobile-icon" /> */}
              </a>
            </li>

            <li className="">
              <Link to="/">
                <span id="list-items">List of books</span>
              </Link>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  );
};
export default Sidebar;
