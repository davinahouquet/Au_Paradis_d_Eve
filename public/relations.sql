USER -> RESERVATION = OneToMany, nom de la propriété dans User "Reservations"
RESERVATION -> ESPACE = ManyToOne, nom de la propriété dans Espace "Réservations"(Collection)/ dans Réservation "Espace"(objet)
ESPACE -> CATEGORIE = ManyToOne, nom de la propriété dans Espace = "Catégorie"
ESPACE -> IMAGE = OneToMany, nom de la propriété dans Espace = "images"