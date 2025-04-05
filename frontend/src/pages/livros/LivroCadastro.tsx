import React, { useEffect, useState } from 'react';
import api from '../../services/api';

const LivroCadastro: React.FC = ({ onLivroSalvo, livroSelecionado, esconderTituloSeModal = true }) => {

    const [livro, setLivro] = useState({
        titulo: "",
        editora: "",
        edicao: "",
        preco: null,
        anoPublicacao: "",
        autores: [],
        assuntos: []
    });

    const [autores, setAutores] = useState<{ id: number, nome: string }[]>([]);
    const [assuntos, setAssuntos] = useState<{ id: number, descricao: string }[]>([]);

    const [mensagem, setMensagem] = useState('');

    const [autorSelecionado, setAutorSelecionado] = useState('');
    const [assuntoSelecionado, setAssuntoSelecionado] = useState('');

    const carregarDados = async () => {
        try {
            const responseAutores = await api.get('/autores');
            setAutores(responseAutores);

            const responseAssuntos = await api.get('/assuntos');
            setAssuntos(responseAssuntos);
        } catch (error) {
            console.error('Erro ao buscar dados:', error);
        }
    };

    // Buscar autores e assuntos ao carregar a página
    useEffect(() => {
            if (livroSelecionado) {
                setLivro({
                    ...livroSelecionado,
                    autores: livroSelecionado.autores.map(a => ({ id: a.id, nome: a.nome })),
                    assuntos: livroSelecionado.assuntos.map(a => ({ id: a.id, descricao: a.descricao }))
                });
            } else {
                setLivro({
                    titulo: "",
                    editora: "",
                    edicao: "",
                    anoPublicacao: "",
                    preco: null,
                    autores: [],
                    assuntos: []
                });
        };

        carregarDados();
    }, [livroSelecionado]);

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

      const handleChangePreco = (e) => {
        const rawValue = e.target.value.replace(/\D/g, '');
        const preco = (parseFloat(rawValue) / 100).toFixed(2);
        setLivro({ ...livro, preco });
      };

    const handleChange = (e) => {
        setLivro({ ...livro, [e.target.name]: e.target.value });
    };

    // Adicionar autor à lista
    const adicionarAutor = () => {
        if (!autorSelecionado) return;
        const autor = autores.find(a => a.id.toString() === autorSelecionado);
        if (autor && !livro.autores.some(a => a.id === autor.id)) {
            setLivro({ ...livro, autores: [...livro.autores, autor] });
        }
        setAutorSelecionado('');
    };

    // Adicionar assunto à lista
    const adicionarAssunto = () => {
        if (!assuntoSelecionado) return;
        const assunto = assuntos.find(a => a.id.toString() === assuntoSelecionado);
        if (assunto && !livro.assuntos.some(a => a.id === assunto.id)) {
            setLivro({ ...livro, assuntos: [...livro.assuntos, assunto] });
        }
        setAssuntoSelecionado('');
    };

    const removerAutor = (nome: string) => {
        setLivro((prevLivro) => ({
            ...prevLivro,
            autores: prevLivro.autores.filter((autor) => autor.nome !== nome)
        }));
    };

    const removerAssunto = (descricao: string) => {
        setLivro((prevLivro) => ({
            ...prevLivro,
            assuntos: prevLivro.assuntos.filter((assunto) => assunto.descricao !== descricao)
        }));
    };

    // Enviar o formulário
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        try {
            if (livroSelecionado) {
                await api.put(`/livros/${livroSelecionado.id}`, livro);
            } else {
                await api.post("/livros", livro);
                setLivro({
                    titulo: "",
                    editora: "",
                    edicao: "",
                    preco: null,
                    anoPublicacao: "",
                    autores: [],
                    assuntos: []
                });
            }

            setMensagem('Livro cadastrado com sucesso!');
            onLivroSalvo();
        } catch (error: any) {
            setMensagem(error.message || 'Erro ao cadastrar livro.');
        }
    };

    return (
        <div className="container mt-4">
            {esconderTituloSeModal && <h2 className="mb-4">Cadastrar Livro</h2>}
            {mensagem && <div className="alert alert-info">{mensagem}</div>}
            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label className="form-label">Preço</label>
                    <input
                        type="text"
                        className="form-control"
                        name="preco"
                        value={formatarPreco(livro.preco)}
                        onChange={handleChangePreco}
                        placeholder="R$ 0,00"
                        required
                    />
                </div>
                <div className="mb-3">
                    <label className="form-label">Título</label>
                    <input type="text" maxLength={40} className="form-control" name="titulo" value={livro.titulo} onChange={handleChange} required />
                </div>
                <div className="mb-3">
                    <label className="form-label">Editora</label>
                    <input type="text"  maxLength={40} className="form-control"name="editora" value={livro.editora} onChange={handleChange} required />
                </div>
                <div className="mb-3">
                    <label className="form-label">Edição</label>
                    <input type="number" className="form-control" name="edicao" value={livro.edicao} onChange={handleChange} required />
                </div>
                <div className="mb-3">
                    <label className="form-label">Ano de Publicação</label>
                    <input type="text" maxLength={4} className="form-control" name="anoPublicacao" value={livro.anoPublicacao} 
                    onChange={(e) => {
                        const valor = e.target.value.replace(/\D/, ''); // Remove caracteres não numéricos
                        if (valor.length <= 4) {
                            handleChange(e);
                        }
                    }}
                    required />
                </div>
                {/* Seleção de Autores */}
                <div className="mb-3">
                    <label className="form-label">Autores</label>
                    <div className="d-flex">
                        <select className="form-control me-2" value={autorSelecionado} onChange={(e) => setAutorSelecionado(e.target.value)}>
                            <option value="">Selecione um autor</option>
                            {autores.map((autor) => (
                                <option key={autor.id} value={autor.id.toString()}>{autor.nome}</option>
                            ))}
                        </select>
                        <button type="button" className="btn btn-secondary" onClick={adicionarAutor}>Adicionar</button>
                    </div>
                    <ul className="list-group mt-2">
                        {livro.autores.map((autor) => (
                            <li
                                key={autor.id}
                                className="list-group-item d-flex justify-content-between align-items-center"
                            >
                                {autor.nome}
                                <button
                                    type="button"
                                    className="btn btn-sm btn-outline-danger"
                                    onClick={() => removerAutor(autor.nome)}
                                >
                                    Remover
                                </button>
                            </li>
                        ))}
                    </ul>
                </div>

                {/* Seleção de Assuntos */}
                <div className="mb-3">
                    <label className="form-label">Assuntos</label>
                    <div className="d-flex">
                        <select className="form-control me-2" value={assuntoSelecionado} onChange={(e) => setAssuntoSelecionado(e.target.value)}>
                            <option value="">Selecione um assunto</option>
                            {assuntos.map((assunto) => (
                                <option key={assunto.id} value={assunto.id.toString()}>{assunto.descricao}</option>
                            ))}
                        </select>
                        <button type="button" className="btn btn-secondary" onClick={adicionarAssunto}>Adicionar</button>
                    </div>
                    <ul className="list-group mt-2">
                        {livro.assuntos.map((assunto) => (
                            <li
                                key={assunto.id}
                                className="list-group-item d-flex justify-content-between align-items-center"
                            >
                                {assunto.descricao}
                                <button
                                    type="button"
                                    className="btn btn-sm btn-outline-danger"
                                    onClick={() => removerAssunto(assunto.descricao)}
                                >
                                    Remover
                                </button>
                            </li>
                        ))}
                    </ul>
                </div>
                <button type="submit" className="btn btn-primary">
                    {livroSelecionado ? "Atualizar" : "Adicionar"}
                </button>
            </form>
        </div>
    );
};

export default LivroCadastro;
