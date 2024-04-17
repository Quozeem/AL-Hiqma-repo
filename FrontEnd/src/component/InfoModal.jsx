import React from "react";
import { useSelector } from "react-redux";

const Modal = ({verify, InputDataValue, Handlersubmit, Modal_cancel ,detailsData}) => {
  

  return (
    // <!-- Modal -->
    <div id="myModal" className="modal">
      <div className="modal-content-purchase">
        <span className="close" onClick={Modal_cancel}>
          ×
        </span>

        <div className="product-purchase-details">
          <div className="purchase-head"></div>
          <div className="purchase-body">
            <div className="table-purchase">
              <form className="form-class" onSubmit={Handlersubmit}>
                <label>Book Name</label>
                <br />
                <input
                  type="text"
                  defaultValue=  { detailsData && detailsData.name || ""}
                  onChange={InputDataValue}
                  name="name"
                  required
                />
                <label>Book ISBN</label>
                <br />
                <input
                  type="text"
                  defaultValue={  detailsData && detailsData.isbn || ""}
                  onChange={InputDataValue}
                  name="isbn"
                  required
                />
                <label>Book Authors</label>
                <br />
                <input
                  type="text"
                  defaultValue={   detailsData && detailsData.authors || ""}
                  onChange={InputDataValue}
                  name="authors"
                  required
                />
                <label> Country</label>
                <br />
                <input
                  type="text"
                  defaultValue={  detailsData && detailsData.country || ""}
                  onChange={InputDataValue}
                  name="country"
                  required
                />
                <label>Book Pages</label>
                <br />
                <input
                  type="number"
                  defaultValue={  detailsData && detailsData.number_of_pages || ""}
                  onChange={InputDataValue}
                  name="number_of_pages"
                  required
                />
                <label>Book Publisher</label>
                <br />
                <input
                  type="text"
                  defaultValue={  detailsData && detailsData.publisher || ""}
                  onChange={InputDataValue}
                  name="publisher"
                  required
                />

                <br />

                <div className="purchase-head-items">
                  <button>{verify ? "Loading..." : "Save"}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
export default Modal;
