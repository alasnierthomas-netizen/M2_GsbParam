<div class="contenuCentre">
    <form action="index.php?uc=admin&action=confirmchangeOrAddProduit" method="post">
        <div class="mb-3 row">
            <label for="description">description</label>
            <div class="col-sm-10">
                <textarea> <?php echo (!empty($description))? $description : "" ?> </textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="prix">prix</label>
            <div class="col-sm-10">
                <input required type="number" value="<?php echo (!empty($prix))? $prix : "" ?>">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="image">nouvelle image</label>
            <div class="col-sm-10">
                <input <?php echo (!empty($image))? "" : "required" ?> type="file">
            </div>
        </div>

        <label for="categorie">categorie</label>
        <select name="categorie" id="categorie">
            <?php foreach($categories as $category){ ?>
                <option value="<?php echo $category->id ?>" <?php echo ($category->id == $idCategorie)? "selected" : ""; ?> ><?php echo $category->libelle ?></option>
            <?php } ?>
        </select>

        <?php
        if (!empty($idProduit))
        {
            ?> <input type="text" id="idProduit" name="idProduit" hidden> <?php
        }
        ?>

        <div class="btnCentre">
            <button class="btn btn-primary" type="submit" name="valider">Valider</button>
        </div>
    </form>
</div>


