<form method="POST" action="/materiais/store">
    <input type="hidden" name="curso_id" value="<?= $_GET['curso_id'] ?>">
    <input type="text" name="titulo" placeholder="Título do Material">
    <textarea name="conteudo" placeholder="Conteúdo"></textarea>
    <select name="tipo">
        <option value="material">Material</option>
        <option value="atividade">Atividade</option>
    </select>
    <button type="submit">Postar</button>
</form>
