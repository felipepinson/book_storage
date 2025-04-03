import React, { useState, useEffect } from 'react';
import api from '../../services/api';

const AutorCadastro = ({ esconderTituloSeModal = true }) => {
    const [nome, setNome] = useState('');
    const [mensagem, setMensagem] = useState('');
    const [autores, setAutores] = useState<{ id: number; nome: string }[]>([]);
    const [autorEditando, setAutorEditando] = useState<{ id: number; nome: string } | null>(null);

    // Carregar autores ao iniciar
    useEffect(() => {
        carregarAutores();
    }, []);

    const carregarAutores = async () => {
        try {
            const response = await api.get('/autores');
            setAutores(response);
        } catch (error) {
            console.error('Erro ao buscar autores:', error);
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            if (autorEditando) {
                // Atualizar autor existente
                await api.put(`/autores/${autorEditando.id}`, { nome });
                setMensagem('Autor atualizado com sucesso!');
            } else {
                // Criar novo autor
                await api.post('/autores', { nome });
                setMensagem('Autor cadastrado com sucesso!');
            }

            setNome('');
            setAutorEditando(null);
            carregarAutores();
        } catch (error: any) {
            setMensagem(error.message || 'Erro ao salvar autor.');
        }
    };

    const handleEditar = (autor: { id: number; nome: string }) => {
        setNome(autor.nome);
        setAutorEditando(autor);
    };

    const handleRemover = async (id: number) => {
        if (!window.confirm("Tem certeza que deseja remover este Autor?")) return;

        try {
            await api.delete(`/autores/${id}`);
            setMensagem('Autor removido com sucesso!');
            carregarAutores();
        } catch (error: any) {
            setMensagem(error.message || 'Erro ao remover autor.');
        }
    };

    return (
        <div className="container mt-4">
            {esconderTituloSeModal && <h2 className="mb-4">{autorEditando ? 'Editar Autor' : 'Cadastrar Autor'}</h2>}
            {mensagem && <div className="alert alert-info">{mensagem}</div>}

            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label className="form-label">Nome do Autor</label>
                    <input
                        type="text"
                        className="form-control"
                        value={nome}
                        onChange={(e) => setNome(e.target.value)}
                        required
                    />
                </div>
                <button type="submit" className="btn btn-primary">
                    {autorEditando ? 'Atualizar' : 'Salvar'}
                </button>
                {autorEditando && (
                    <button type="button" className="btn btn-secondary ms-2" onClick={() => {
                        setNome('');
                        setAutorEditando(null);
                    }}>
                        Cancelar
                    </button>
                )}
            </form>

            <h3 className="mt-4">Autores Cadastrados</h3>
            <ul className="list-group mt-2">
               {autores?.map((autor) => (
                    <li key={autor.id} className="list-group-item d-flex justify-content-between align-items-center">
                        {autor.nome}
                        <div>
                            <button className="btn btn-warning btn-sm me-2" onClick={() => handleEditar(autor)}>Editar</button>
                            <button className="btn btn-danger btn-sm" onClick={() => handleRemover(autor.id)}>Remover</button>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default AutorCadastro;
