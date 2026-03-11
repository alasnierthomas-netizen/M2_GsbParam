<div class="contenuCentre">
    <form action="index.php?uc=admin&action=confirmchangeOrAddProduit" method="post" enctype="multipart/form-data">
        <input type="text" hidden name="idProduit" value="<?php echo (!empty($idProduit))? $idProduit : "" ?>">
        <div class="mb-3 row">
            <label for="description">description</label>
            <div class="col-sm-10">
                <textarea name="description" required> <?php echo (!empty($description))? $description : "" ?> </textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="prix">prix</label>
            <div class="col-sm-10">
                <input name="prix" required type="number" value="<?php echo (!empty($prix))? $prix : "" ?>">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="image">nouvelle image</label>
            <div class="col-sm-10">
                <input name="image" <?php echo (!empty($image))? "" : "required" ?> type="file">
            </div>
        </div>

        <label for="categorie">categorie</label>
        <select name="idCategorie" id="categorie">
            <?php foreach($categories as $category){ ?>
                <option value="<?php echo $category->id ?>" <?php echo ($category->id == $idCategorie)? "selected" : ""; ?> ><?php echo $category->libelle ?></option>
            <?php } ?>
        </select>


        <div class="btnCentre">
            <button class="btn btn-primary" type="submit" name="valider">Valider</button>
        </div>
    </form>
</div>


