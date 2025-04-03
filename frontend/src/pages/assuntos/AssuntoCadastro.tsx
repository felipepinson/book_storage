import React, { useState, useEffect } from 'react';
import api from '../../services/api';

const AssuntoCadastro = ({ esconderTituloSeModal = true }) => {
    const [descricao, setDescricao] = useState('');
    const [mensagem, setMensagem] = useState('');
    const [assuntos, setAssuntos] = useState<{ id: number; descricao: string }[]>([]);
    const [assuntoEditando, setAssuntoEditando] = useState<{ id: number; descricao: string } | null>(null);

    useEffect(() => {
        carregarAssuntos();
    }, []);

    const carregarAssuntos = async () => {
        try {
            const response = await api.get('/assuntos');
            setAssuntos(response);
        } catch (error) {
            console.error('Erro ao buscar Assuntos:', error);
        }
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            if (assuntoEditando) {
                await api.put(`/assuntos/${assuntoEditando.id}`, { descricao });
                setMensagem('Assunto atualizado com sucesso!');
            } else {
                await api.post('/assuntos', { descricao });
                setMensagem('Assunto cadastrado com sucesso!');
            }

            setDescricao('');
            setAssuntoEditando(null);
            carregarAssuntos();
        } catch (error: any) {
            setMensagem(error.message || 'Erro ao salvar assunto.');
        }
    };

    const handleEditar = (assunto: { id: number; descricao: string }) => {
        setDescricao(assunto.descricao);
        setAssuntoEditando(assunto);
    };

    const handleRemover = async (id: number) => {
        if (!window.confirm("Tem certeza que deseja remover este Assunto?")) return;

        try {
            await api.delete(`/assuntos/${id}`);
            setMensagem('Assunto removido com sucesso!');
            carregarAssuntos();
        } catch (error: any) {
            setMensagem(error.message || 'Erro ao remover assunto.');
        }
    };

    return (
        <div className="container center">
            { esconderTituloSeModal && <h2 className="mb-4">Cadastrar Assunto</h2> }
            {mensagem && <div className="alert alert-info">{mensagem}</div>}
            <form onSubmit={handleSubmit}>
                <div className="mb-3">
                    <label className="form-label">Descrição</label>
                    <input
                        type="text"
                        className="form-control"
                        value={descricao}
                        onChange={(e) => setDescricao(e.target.value)}
                        required
                    />
                </div>
                <button type="submit" className="btn btn-primary">Salvar</button>
            </form>
            <h3 className="mt-4">Assuntos Cadastrados</h3>
            <ul className="list-group mt-2">
               {assuntos?.map((assunto) => (
                    <li key={assunto.id} className="list-group-item d-flex justify-content-between align-items-center">
                        {assunto.descricao}
                        <div>
                            <button className="btn btn-warning btn-sm me-2" onClick={() => handleEditar(assunto)}>Editar</button>
                            <button className="btn btn-danger btn-sm" onClick={() => handleRemover(assunto.id)}>Remover</button>
                        </div>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default AssuntoCadastro;
