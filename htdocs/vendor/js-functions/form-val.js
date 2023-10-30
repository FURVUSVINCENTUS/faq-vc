
import { modifcation, modifcation.pswd_adm, modification.pswd_webm, modifcation.pswd_usr } from "../../admin.php";

export function validateForm ( modification, modification.pswd_adm, modification.pswd_webm, modification.pswd_usr ){
  let admin = document.forms["modification"]["pswd_adm"].value;
  let webmaster = document.forms["modification"]["pswd_webm"].value;
  let user = document.forms["modification"]["pswd_usr"].value;
  if (admin == "" && webmaster == "" && user == "") {
    alert("Vous devez vous authentifier");
    return false;
  }
}
