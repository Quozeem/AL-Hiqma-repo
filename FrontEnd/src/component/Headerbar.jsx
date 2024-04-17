import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { useEffect } from "react";
import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import { setdataNotAccount } from "./Redux/Index";

const Headerbar = () => {
  const dispatch = useDispatch();
  const Navigate = useNavigate();
  
  /******* Category *****/

  /************* Window size ******/

  /********* NOtification  ****/
  const [openBOx, setopenBOx] = useState(false);
  const [errorCode, seterrorCode] = useState();
  
  const { message } = useSelector(
    (state) => state.Index
  );
  
  return (
    <>
      <header>
        <div className="admin-header left-sec"></div>
        <div className="admin-header mid-sec">
          {/* <img src={logo} alt="" /> */}
        </div>
        <div className="admin-header bell">
          <span>2</span>
        </div>
        <div className="admin-header right-sec" style={{ cursor: "pointer" }}>
          <span> Logout </span>
        </div>
        {/* <hr/> */}
      </header>
      {/* <div className="loading-wave"></div> */}

      {message ? (
        <div
          className="notification-message-success"
        >
          <div className="notice-head">
            <li>{message}</li>
            <li>{/* <Fa onClick={CloseMessage} /> */}</li>
          </div>
          <hr />
        </div>
      ) : null}

      {openBOx ? (
        <>
          <div className="notification">
            <div className="notice-head">
              <li>notifications</li>
              <li>clear all</li>
            </div>
            <hr />
            <div className="notification-messages">
              <ul>
                <li>
                  <span></span>
                </li>
                <li>
                  <p>Larry Added a new task today 28th of August 2023</p>
                </li>{" "}
              </ul>
              <ul>
                <li>
                  <span>{/* <img src={avatar} /> */}</span>
                </li>
                <li>
                  <p>Larry Added a new task</p>
                </li>{" "}
              </ul>
              <ul>
                <li>
                  <span>{/* <img src={avatar} /> */}</span>
                </li>
                <li>
                  <p>Larry Added a new task</p>
                </li>{" "}
              </ul>
              <ul>
                <li>
                  <span>{/* <img src={avatar} /> */}</span>
                </li>
                <li>
                  <p>Larry Added a new task</p>
                </li>{" "}
              </ul>
              <ul>
                <li>
                  <span>{/* <img src={avatar} /> */}</span>
                </li>
                <li>
                  <p>Larry Added a new task</p>
                </li>{" "}
              </ul>
            </div>
            <div className="viewall">
              <a href="">view all notifications</a>
            </div>
          </div>
        </>
      ) : null}
    </>
  );
};
export default Headerbar;
