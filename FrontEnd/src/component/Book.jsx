import react, { useRef, useEffect } from "react";
import Headerbar from "./Headerbar";
import Sidebar from "./Sidebar";
import { useHistory, useLocation, useNavigate } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { useState } from "react";
import Modal from "./InfoModal";
import axios from "axios";
import { AiTwotoneDelete, AiOutlineEdit } from "react-icons/ai";
import { setEditbook, setbook, setdataNotAccount,  setmessage } from "./Redux/Index";

const Book = () => {
  const { dataNotAccount, Category, book } = useSelector(
    (state) => state.Index
  );
  /************* Details Info ********/
  const [openmodal, setopenmodal] = useState(false);
  const [detailsData, setdetailsData] = useState([]);

  const [verify, setverify] = useState(false);

  /******************* Book ADDED  ***********/

  function InputDataValue(event) {
    const { name, value, type } = event.target;
    setdetailsData((previousdata) => {
return {
        ...previousdata,
        [name]: type === "number" ? value: value,
}
    })
  }

  function Handlersubmit(e) {
    e.preventDefault();
    dispatch(setmessage())
    setverify(true);
  }
  const dispatch = useDispatch();
  const [refreshdocument, setrefreshdocument] = useState(false);

  useEffect(() => {
    async function Create() {
      {
        if (verify && !editId) {
          axios.post(`api/v1/books`, detailsData).then((response) => {
            setverify(false);
            setopenmodal(false);
            dispatch(setmessage(response.data.message))
            setrefreshdocument(true);
        
setdetailsData({});
          });
        }
      }
    }
    Create();
  }, [verify, detailsData]);

  /******* REFRESH Book LIST *****/
  useEffect(() => {
    async function BookList() {
      if (refreshdocument) {
        axios
          .get("api/v1/books")
          .then((res) => {
            dispatch(setdataNotAccount("Table is Currently Empty"));
            dispatch(setbook(res.data.data));
            setrefreshdocument(false);
          })
          .catch((error) => {});
      }
    }
    BookList();
  }, [refreshdocument]);
  /********* END *****/

  /************** END *************/
  function Modal_cancel() {
    setopenmodal(false);
    setdetailsData({});
    dispatch(setmessage())
    dispatch(setEditbook());
    setverify(false);
    seteditId()
  }

  /*============= BOOKS ==========*/
  const formatDate = (inputDate) => {
    const dateObject = new Date(inputDate);
    const options = { year: "numeric", month: "long", day: "numeric" };
    return dateObject.toLocaleDateString(undefined, options);
  };
  /******************* SEARCH  Query ************/

  const [searchQuery, setSearchQuery] = useState("");
  const [filteredHistory, setFilteredHistory] = useState([]);

  const handleSearchChange = (event) => {
    const query = event.target.value;
    setSearchQuery(query);

    const filtered = book.filter((bookquery) => {
      // Customize the filtering logic based on your requirements
      const lowerCaseQuery = query.toLowerCase();

      // Add null checks for properties that might be null or undefined
      const book_name = bookquery.name ? bookquery.name.toLowerCase() : "";
      const author = bookquery.authors ? bookquery.authors.toLowerCase() : "";
      // Check if the query matches any part of the date string

      return (
        book_name.includes(lowerCaseQuery) || author.includes(lowerCaseQuery)
      );
    });

    setFilteredHistory(filtered);
  };

  /****************** END *****/

  //   Pagination
  const [currentPage, setCurrentPage] = useState(1);
  const [productsPerPage, setproductsPerPage] = useState(10);

  // Calculate the index of the first and last product to show on the current page
  const indexOfLastProduct = currentPage * productsPerPage;
  const indexOfFirstProduct = indexOfLastProduct - productsPerPage;

  // Extract the subset of product data to show on the current page

  let serialNumberdeposit = 1;
  const currentProductCategory = book.slice(
    indexOfFirstProduct,
    indexOfLastProduct
  );
  //  Generate the Withdrawal components for the current page
  const displayedHistory = searchQuery
    ? filteredHistory
    : currentProductCategory;

    /********  Edit Query */
    const [editId,seteditId]=useState()
    function Editbook(id,name,authors,number_of_pages,
      publisher,country, isbn)
    {
      setdetailsData({
        id:id,
        authors:authors,
        name:name,
        number_of_pages:number_of_pages,
        publisher:publisher,
        country:country,
        isbn:isbn

      });
      setopenmodal(true);
      dispatch(setmessage())
seteditId(true)
    }

    /******* Update Book LIST *****/
    useEffect(() => {
      async function BookEdit() {
        if   (verify &&  editId) {
          axios
            .patch(`api/v1/books`,detailsData)
            .then((res) => {
     
              dispatch(setEditbook(res.data.data));
              setverify(false);
              seteditId(false);

      
              setopenmodal(false);
              dispatch(setmessage(res.data.message))
              setrefreshdocument(true);
          
  setdetailsData({});
            
            })
            .catch((error) => {});
        }
      }
      BookEdit();
    }, [editId, verify]);

     /******* Delete Book LIST *****/
     const [delId,setdelId]=useState()
     function Deletebook(id)
     {
      setdelId(id)
     }
     useEffect(() => {
      async function BookDelete() {
        if   (delId) {
          axios
            .delete(`api/v1/books/${delId}`)
            .then((res) => {
     
          
              setopenmodal(false);
              dispatch(setmessage(res.data.message))
              setrefreshdocument(true);
          
            
            })
            .catch((error) => {});
        }
      }
      BookDelete();
    }, [delId]);
  /********* END *****/

  const BookTable = displayedHistory.map((sourceElement, index) => {
    return (
      <tbody key={index}>
        <tr>
          <td>{serialNumberdeposit++}</td>
          <td>{sourceElement.name}</td>
          <td>{sourceElement.authors}</td>
          <td>{sourceElement.number_of_pages}</td>
          <td>{sourceElement.publisher}</td>
          <td>{sourceElement.release_date}</td>
          <td>
            <AiOutlineEdit  onClick={()=>Editbook(sourceElement.id,sourceElement.name,sourceElement.authors,sourceElement.number_of_pages,
              sourceElement.publisher,sourceElement.country, sourceElement.isbn)}/>
          </td>
          <td>
            <AiTwotoneDelete  onClick={()=>Deletebook(sourceElement.id)}/>
          </td>
        </tr>
      </tbody>
    );
  });
  /*********** Next Previous button *************/
  // Calculate the total number of pages based on the total number of products
  const totalPages = Math.ceil(Category.length / productsPerPage);

  // Handle the click event for the "next" button
  const handleNextClick = () => {
    if (currentPage < totalPages) {
      setCurrentPage(currentPage + 1);
    }
  };

  // Handle the click event for the "previous" button
  const handlePreviousClick = () => {
    if (currentPage > 1) {
      setCurrentPage(currentPage - 1);
    }
  };
  const handleRowChange = (event) => {
    const newValue = parseInt(event.target.value);
    setproductsPerPage(newValue);
  };
  // Create a ref for the table component to print
  const tableRef = useRef(null);
  // Function to export the table data to Excel
  return (
    <>
      <div className="admin-wrapper-page" style={{ gap: "12em" }}>
        <Headerbar />
        <div className="admin-wrapper-col-4" id="sidenav">
          <div className="admin-main">
            <div className="admin-sidebar">
              <Sidebar />
            </div>
          </div>
        </div>
        <div className="admin-wrapper-col-8">
          <div className="admin-topic">Books</div>
          <div className="admin-form-group-add">
            {/* <h4>Deposits</h4> */}
            <div className="admin-col">
              <div className="actions"></div>

              <div className="admin-search">
                <label></label>
                <div className="admin-btn">
                  <button onClick={() => setopenmodal(true)}>Add Book</button>
                </div>{" "}
              </div>
            </div>
            <div className="admin-deposit">
              <div className="admin-out">
                {/* <h4>withdrawal</h4> */}
                <div
                  className="table-responsive"
                  id="printable-area"
                  ref={tableRef}
                >
                  {BookTable.length > 0 ? (
                    <table id="tableToConvert" className="admin-out">
                      <thead>
                        <tr>
                          <th>S/N</th>
                          <th>Book Name</th>
                          <th>Book Authors</th>
                          <th> Number of pages</th>
                          <th> Publisher</th>
                          <th> Release Date</th>
                          <th> Edit</th>
                          <th> Delete</th>

                          {/* <th>Action</th> */}
                        </tr>
                      </thead>
                      {BookTable}
                    </table>
                  ) : (
                    <h3>{dataNotAccount}</h3>
                  )}
                </div>
              </div>
            </div>{" "}
            <div className="admin-down">
              <div className="rowsperpage">
                <label htmlFor="rowsPerPage">Rows per Page:</label>
                <select
                  id="rowsPerPage"
                  onChange={handleRowChange}
                  value={productsPerPage}
                >
                  <option value="10">10</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </div>
              <div className="admin-btn">
                <button
                  className={currentPage === 1 ? "disabledButton" : ""}
                  id="prevBtn"
                  onClick={handlePreviousClick}
                >
                  Previous
                </button>
                <button
                  className={currentPage === totalPages ? "disabledButton" : ""}
                  id="nextBtn"
                  onClick={handleNextClick}
                >
                  Next
                </button>
              </div>
            </div>{" "}
          </div>{" "}
        </div>
      </div>
      {openmodal ? ( // <!-- Modal -->
        <Modal
        detailsData={detailsData}
          Modal_cancel={Modal_cancel}
          verify={verify}
          Handlersubmit={Handlersubmit}
          InputDataValue={InputDataValue}
        />
      ) : null}
    </>
  );
};
export default Book;
