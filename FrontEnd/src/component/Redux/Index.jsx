import { createSlice } from "@reduxjs/toolkit";

const initialState = {
  book: [],
  Category: [],
  dataNotAccount: "",
  message: "",
  Editbook: "",
};

export const Index = createSlice({
  name: "counter",
  initialState,
  reducers: {
    setbook: (state, action) => {
      state.book = action.payload;
    },
    setdataNotAccount: (state, action) => {
      state.dataNotAccount = action.payload;
    },
    setmessage: (state, action) => {
      state.message = action.payload;
    },
    setEditbook: (state, action) => {
      state.Editbook = action.payload;
    },
  },
});

// Action creators are generated for each case reducer function
export const { setbook, setEditbook, setmessage,setdataNotAccount } = Index.actions;

export default Index.reducer;
