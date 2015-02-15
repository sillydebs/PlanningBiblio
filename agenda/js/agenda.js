/*
Planning Biblio, Version 1.9
Licence GNU/GPL (version 2 et au dela)
Voir les fichiers README.md et LICENSE
Copyright (C) 2011-2015 - Jérôme Combes

Fichier : agenda/js/agenda.js
Création : 22 janvier 2015
Dernière modification : 22 janvier 2015
Auteur : Jérôme Combes, jerome@planningbilbio.fr

Description :
Fichier regroupant les fonctions JavaScript utiles à l'affichage de l'agenda
*/

$(document).ready(function(){
  // Redimensionne les colonnes de façon à ce qu'elles soient toutes de la même largeur (largeur de la plus grande d'entre elle)
  var width=0;
  $("#tab_agenda th").each(function(){
    width=$(this).width()>width?$(this).width():width;
  });
  $("#tab_agenda th").each(function(){
    $(this).css("width",width);
  });
  
  // Mise en forme des messages d'erreur
  errorHighlight($(".information"),"error");
});