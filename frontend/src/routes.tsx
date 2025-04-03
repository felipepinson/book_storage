import { BrowserRouter, Routes, Route } from "react-router-dom";
import Livros from "./pages/Livros";
import LivroCadastro from "./pages/LivroCadastro";
import AutorCadastro from "./pages/autores/AutorCadastro";
import AssuntoCadastro from "./pages/assuntos/AssuntoCadastro";
import NotFound from "./pages/NotFound";

export default function AppRoutes() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="*" element={<NotFound />} />
        <Route path="/" element={<Livros />} />
        <Route path="/livros" element={<LivroCadastro />} />
        <Route path="/autores" element={<AutorCadastro />} />
        <Route path="/assuntos" element={<AssuntoCadastro />} />
      </Routes>
    </BrowserRouter>
  );
}
