<?php


use Drupal\taxonomy\Entity\Term;

/**
 *
 * Init codes
 */
function insign_early_access_deploy_init_codes() {
  $codes = [
    "6wYO16Z4dg5ICQ8",
    "OWMSeRFnCARdRzZ",
    "nyqWE4TA0jcCP5A",
    "qBD7eKtlKrzhnBC",
    "9exuCciUc6qMmOC",
    "QbgDL2TOCcNZ1iJ",
    "gj312G9zmchnBG2",
    "Ayj0gPK6CM3PMoI",
    "iPS0Z7se5E3xg44",
    "kL5AyfvBheooLBL",
    "0gE34XpDwOrtGqY",
    "epmKAnICWhSBBwY",
    "f3UgOaVVZPgpO20",
    "GLZMDXPeIZDGmdR",
    "Bkjznw4PUGpjkAo",
    "Vq7uS1QViatzCi3",
    "310yJAl4nPwTLNb",
    "IyqeBqN5wvCnkUw",
    "cgJqqzmG9Y2h3kq",
    "2ddyJH3CsN3kkoH",
    "RHdfVxrGXVydTjC",
    "bYrqlnSvL2Sq0yS",
    "vOL2iKY0p163ZqN",
    "hgvQNBGbUYgzGCe",
    "KrXI6OoLaskTAth"
  ];

  foreach (array_unique($codes) as $code) {
    Term::create([
      'vid' => 'code_a_usage_unique',
      'name' => $code,
      'field_status' => FALSE
    ])->save();
  }
}
