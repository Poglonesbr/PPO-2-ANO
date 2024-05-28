
function addComment() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var comment = document.getElementById('comment').value;

    var commentList = document.getElementById('commentList');
    var listItem = document.createElement('li');
    listItem.innerHTML = `<strong>${name}</strong> (${email}): ${comment}`;
    commentList.appendChild(listItem);

    // Limpa os campos após adicionar o comentário
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('comment').value = '';
}