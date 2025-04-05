import { useEffect, useState } from 'react';
import api, { BASE_URL } from '../../services/api';
import { Modal, Button } from "react-bootstrap";
import LivroCadastro from "./LivroCadastro";
import AutorCadastro from "../autores/AutorCadastro";
import AssuntoCadastro from "../assuntos/AssuntoCadastro";
import '../../assets/styles/livros.css'

const Livros = () => {
    const [livros, setLivros] = useState([]);
    const [livroSelecionado, setLivroSelecionado] = useState(null);
    const [showModalNovoLivro, setShowModalNovoLivro] = useState(false);
    const [showModalNovoAutor, setShowModalNovoAutor] = useState(false);
    const [showModalNovoAssunto, setShowModalNovoAssunto] = useState(false);

    const formatarPreco = (valor) => {
        if (!valor) return '';

        let numero = typeof valor === 'string'
          ? parseFloat(valor.replace(',', '.'))
          : parseFloat(valor);

        if (isNaN(numero)) return '';

        return numero.toLocaleString('pt-BR', {
          style: 'currency',
          currency: 'BRL',
        });
    };

    const carregarLivros = async () => {
        try {
            const data = await api.get('/livros');
            setLivros(data);
        } catch (error) {
            console.error('Erro ao buscar livros:', error);
        }
    };

    async function removerLivro(id) {
        if (!window.confirm("Tem certeza que deseja remover este livro?")) return;

        try {
            await api.delete(`/livros/${id}`);
            carregarLivros();
        } catch (error) {
            console.error("Erro ao remover livro:", error.message);
        }
    }

    useEffect(() => {
        carregarLivros();
    }, []);

    const handleShowModalLivro = (livro = null) => {
        setLivroSelecionado(livro);
        setShowModalNovoLivro(true);
    };

    return (
        <div className="container d-flex flex-column align-items-center justify-content-tops min-vh-100, min-vw-100 text-center">
            <div className="row justify-content-center align-items-center">
                <h1>Lista de Livros</h1>
                <div className="my-3">
                    <Button className="mb-4 mx-2" variant="primary" onClick={() => setShowModalNovoAutor(true)}>
                        + Autores
                    </Button>
                    <Button className="mb-4 mx-2" variant="primary" onClick={() => setShowModalNovoAssunto(true)}>
                        + Assuntos
                    </Button>
                    <Button className="mb-4 mx-2" variant="success" onClick={() => handleShowModalLivro(null)}>
                        + Novo Livro
                    </Button>
                    <a
                    href={`${BASE_URL}/relatorios/livros-por-autor`}
                    className="btn btn-secondary mb-4 mx-2"
                    target="_blank"
                    rel="noopener noreferrer"
                    >
                        Exportar
                    </a>
                </div>
            </div>
            <div className="row justify-content-center">
                {livros.map(livro => (
                    <div key={livro.id} className="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center mb-4">
                        <div className="card livro-card shadow-sm">
                            <div className="card-body">
                                <h5 className="card-title">{livro.titulo}</h5>
                                <h6>Edição: {livro.edicao}</h6>
                                <h4>{formatarPreco(livro.preco)}</h4>
                                <p className="card-text">
                                    <strong>Autores:</strong><br />
                                    <span className="fst-italic">{livro.autores.map(autor => autor.nome).join(', ')}</span>
                                </p>
                                <h6><strong>Editora:</strong><br /> {livro.editora} <br /> {livro.anoPublicacao}</h6>
                                <p className="card-text">
                                    <strong>Assuntos    :</strong><br />
                                    <span className="fst-italic">{livro.assuntos.map(assunto => assunto.descricao).join(', ')}</span>
                                </p>
                                <a href="#" className="btn btn-primary mx-2" onClick={() => handleShowModalLivro(livro)}>Editar</a>
                                <a href="#" className="btn btn-danger mx-2" onClick={() => removerLivro(livro.id)}>Remover</a>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
            {/* Modal Novo Livro */}
            <Modal show={showModalNovoLivro} onHide={() => setShowModalNovoLivro(false)} size="lg">
                <Modal.Header closeButton>
                    <Modal.Title>
                        {livroSelecionado ? 'Editar Livro' : 'Adicionar Livro'}
                    </Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <LivroCadastro esconderTituloSeModal={false} livroSelecionado={livroSelecionado} onLivroSalvo={carregarLivros} />
                </Modal.Body>
            </Modal>
            {/* Modal Novo Autor */}
            <Modal show={showModalNovoAutor} onHide={() => setShowModalNovoAutor(false)} size="lg">
                <Modal.Header closeButton>
                    <Modal.Title>Autores</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <AutorCadastro esconderTituloSeModal={false} fecharModal={() => setShowModalNovoAutor(false)} />
                </Modal.Body>
            </Modal>
            {/* Modal Novo Assunto */}
            <Modal show={showModalNovoAssunto} onHide={() => setShowModalNovoAssunto(false)} size="lg">
                <Modal.Header closeButton>
                    <Modal.Title>Assuntos</Modal.Title>
                </Modal.Header>
                <Modal.Body>
                    <AssuntoCadastro esconderTituloSeModal={false} fecharModal={() => setShowModalNovoAssunto(false)} />
                </Modal.Body>
            </Modal>
        </div>
    );
};

export default Livros;
