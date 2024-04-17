import { configureStore } from "@reduxjs/toolkit";
import Index from "./Index";
export const store = configureStore({
  reducer: {
    Index: Index,
  },
});
