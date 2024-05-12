import { createSlice } from "@reduxjs/toolkit";

const initialState = {
  book: [],
  Category: [],
  dataNotAccount: "",
  testmessage: "",
  testEditbook: "",
};

export const Index = createSlice({
  name: "counters",
  initialState,
  reducers: {
    setbooks: (state, action) => {
      state.book = action.payload;
    },
    setdataNotAccounts: (state, action) => {
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
