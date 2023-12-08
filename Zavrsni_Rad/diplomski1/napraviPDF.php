<?php
include("konekcija.php");
require_once('tcpdf/tcpdf.php'); 

if (isset($_GET['uvid'])) {
    $id=$_GET['idP'];
    $idD=$_GET['idD'];
    $query=mysqli_query($conn,"SELECT * from stavke_porudzbine where ID_PORUDZBINE=$id");
    $query1=mysqli_query($conn,"SELECT * from porudzbine where ID_PORUDZBINE=$id");
    $red=mysqli_fetch_assoc($query1);
    if($red['ID_DOSTAVLJACA']!=NULL){
        $query2=mysqli_query($conn,"SELECT * from dostavljac where ID_DOSTAVLJACA=$idD");
        $red2=mysqli_fetch_assoc($query2);
    }
    $pdf = new TCPDF('P', 'MM', 'A4', true, 'UTF-8', false);

    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 12);
    
    if(mysqli_num_rows($query)>0){
        $html = '<table>
        <tr>
            <th><b>ID_stavke</b></th>
            <th><b>Naziv stavke</b></th>
            <th><b>Količina</b></th>
            <th><b>Napomena</b></th>
            <th><b>Cena</b></th>
            <th><b>Restoran</b></th>
        </tr><hr>';

        while ($podaci = mysqli_fetch_assoc($query)) {
            $html .= '<tr>
                <td>' . $podaci['ID_STAVKE'] . '</td>
                <td>' . $podaci['NAZIV_STAVKE_P'] . '</td>
                <td>' . $podaci['KOLIČINA']. '</td>
                <td>' . $podaci['NAPOMENA'] . '</td>
                <td>' . $podaci['CENA_STAVKE'] . ' rsd</td>
                <td>' . $podaci['NAZIV_RESTORANA'] . '</td>
            </tr><hr>';
        }

        $html .= '</table>';
        
        $pdf->WriteHTML($html);
        if($red['ID_DOSTAVLJACA']!=0){
            $additionalHtml = '<br><br><br><table>
            <tr>
                <td>Dostavljač:</td>
                <td>'.$red2['IME_D'].'</td>
            </tr>
            <tr>
                <td>Kontakt telefon:</td>
                <td>'.$red2['BROJ_TELEFONA_D'].'</td>
            </tr>
            <tr>
                <td>Ukupna cena porudžbine:</td>
                <td>'. $red['UKUPNA_CENA'] . ' rsd</td>
            </tr>
            <tr>
                <td>Status porudžbine:</td>
                <td>'. $red['STATUS_PORUDZBINE'] . '</td>
            </tr>
        </table>';
        }else{
            $additionalHtml = '<table>

                <tr>
                    <td>Ukupna cena porudžbine:</td>
                    <td>'. $red['UKUPNA_CENA'] . ' rsd</td>
                </tr>
                <tr>
                    <td>Status porudžbine:</td>
                    <td>'. $red['STATUS_PORUDZBINE'] . '</td>
                </tr>
            </table>';
        }
            
        $pdf->WriteHTML($additionalHtml);
    }

    $pdf->Output('UvidPorudzbine.pdf', 'I'); 
}
if (isset($_GET['uvid1'])) {
    $id=$_GET['idP'];
    $query=mysqli_query($conn,"SELECT * from stavke_porudzbine where ID_PORUDZBINE=$id");
    $query1=mysqli_query($conn,"SELECT * from porudzbine where ID_PORUDZBINE=$id");
    $red=mysqli_fetch_assoc($query1);
    $pdf = new TCPDF('P', 'MM', 'A4', true, 'UTF-8', false);
    $podatak=$red['ID_KORISNIKA'];
    $query2=mysqli_query($conn,"SELECT * from korisnik where ID_KORISNIKA=$podatak");
    $row2=mysqli_fetch_assoc($query2);
    $pdf->AddPage();
    $pdf->SetFont('dejavusans', '', 12);
    
    if(mysqli_num_rows($query)>0){
        $html = '<table>
        <tr>
            <th><b>ID_porudžbine</b></th>
            <th><b>ID_stavke</b></th>
            <th><b>Naziv stavke</b></th>
            <th><b>Cena</b></th>
            <th><b>Restoran</b></th>
        </tr><hr>';

        while ($podaci = mysqli_fetch_assoc($query)) {
            $html .= '<tr>
                <td>' . $podaci['ID_PORUDZBINE'] . '</td>
                <td>' . $podaci['ID_STAVKE'] . '</td>
                <td>' . $podaci['NAZIV_STAVKE_P'] . '</td>
                <td>' . $podaci['CENA_STAVKE'] . ' rsd</td>
                <td>' . $podaci['NAZIV_RESTORANA'] . '</td>
            </tr><hr>';
        }

        $html .= '</table>';
        
        $pdf->WriteHTML($html);
        $additionalHtml = '<br><br><br><table>
                <tr>
                    <td>Osoba koja naručuje: </td>
                    <td>'.$row2['IME'].' '.$row2['PREZIME'].'</td>
                </tr>
                <tr>
                    <td>Kontakt telefon: </td>
                    <td>'.$row2['BROJ_TELEFONA'].'</td>
                </tr>
                <tr>
                    <td>Ukupna cena porudžbine:</td>
                    <td>'. $red['UKUPNA_CENA'] . ' rsd</td>
                </tr>
                <tr>
                    <td>Adresa dostave:</td>
                    <td>'. $red['ADRESA_DOSTAVE'] .'</td>
                </tr>
            </table>';
            
        $pdf->WriteHTML($additionalHtml);
    }

    $pdf->Output('UvidPorudzbine.pdf', 'I'); 
}
?>
