


// je récupère tous les éléments qui ont pour classe "js-admin-article-delete" (mes boutons de suppression)
// je les stocke dans une variable
const deleteArticleButtons = document.querySelectorAll('.jsDeleteButton');

// pour chaque bouton de suppression trouvé
deleteArticleButtons.forEach((deleteArticleButton)=> {

    // on ajoute un event listener "click"
    // donc on attend que le bouton soit cliqué
    // quand il est cliqué, on execute une fonction de callback
    deleteArticleButton.addEventListener('click', ()=> {
        
        // on prend l'élément HTML suivant (c'est à dire ici la popup)
        const popUp = deleteArticleButton.nextElementSibling;

        console.log(popUp);
        // et on l'affiche en modifiant son display en CSS
        popUp.style.display = 'block';
    });
})