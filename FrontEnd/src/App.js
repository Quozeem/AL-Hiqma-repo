import "./App.css";
import {
  BrowserRouter as Router,
  Routes,
  Switch,
  Route,
  useParams,
} from "react-router-dom";
import Book from "./component/Book";

function App() {
  return (
    <>
      <Router>
        <Routes>
          <Route exact element={<Book />} path="/" />
        </Routes>
      </Router>
    </>
  );
}

export default App;
