<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Company controller.
 *
 * @Route("/hotel")
 */
class DistributionController extends Controller {

    public function createDistribution($habitacionesCant, $adultsSelector1, $adultsSelector2, $adultsSelector3, $adultsSelector4, $childrenSelectOne, $childrenSelectTwo, $childrenSelectTree, $childrenSelectFour, $OneChildrenOne, $OneChildrenTwo, $OneChildrenTree, $OneChildrenFour, $OneChildrenFive, $OneChildrenSix, $TwoChildrenOne, $TwoChildrenTwo, $TwoChildrenTree, $TwoChildrenFour, $TwoChildrenFive, $TwoChildrenSix, $TreeChildrenOne, $TreeChildrenTwo, $TreeChildrenTree, $TreeChildrenFour, $TreeChildrenFive, $TreeChildrenSix, $FourChildrenOne, $FourChildrenTwo, $FourChildrenTree, $FourChildrenFour, $FourChildrenFive, $FourChildrenSix) {

        switch ($childrenSelectOne) {

            case 0:
                $distribucion = $adultsSelector1;
                break;
            case 1:
                $distribucion = $adultsSelector1 . "-" . $OneChildrenOne;
                break;
            case 2:
                $distribucion = $adultsSelector1 . "-" . $OneChildrenOne . "-" . $OneChildrenTwo;
                break;
            case 3:
                $distribucion = $adultsSelector1 . "-" . $OneChildrenOne . "-" . $OneChildrenTwo . "-" . $OneChildrenTree;
                break;
            case 4:
                $distribucion = $adultsSelector1 . "-" . $OneChildrenOne . "-" . $OneChildrenTwo . "-" . $OneChildrenTree . "-" . $OneChildrenFour;
                break;
            case 5:
                $distribucion = $adultsSelector1 . "-" . $OneChildrenOne . "-" . $OneChildrenTwo . "-" . $OneChildrenTree . "-" . $OneChildrenFour . "-" . $OneChildrenFive;
                break;
            case 6:
                $distribucion = $adultsSelector1 . "-" . $OneChildrenOne . "-" . $OneChildrenTwo . "-" . $OneChildrenTree . "-" . $OneChildrenFour . "-" . $OneChildrenFive . "-" . $OneChildrenSix;
                break;
        }

        if ($habitacionesCant > 1) {
            if ($habitacionesCant == 2) {
                switch ($childrenSelectTwo) {

                    case 0:
                        $distribucion .= "!" . $adultsSelector2;
                        break;

                    case 1:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne;
                        break;
                    case 2:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo;
                        break;
                    case 3:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree;
                        break;
                    case 4:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour;
                        break;
                    case 5:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour . "-" . $TwoChildrenFive;
                        break;
                    case 6:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour . "-" . $TwoChildrenFive . "-" . $TwoChildrenSix;
                        break;
                }
            } elseif ($habitacionesCant == 3) {

                switch ($childrenSelectTwo) {

                    case 0:
                        $distribucion .= "!" . $adultsSelector2;
                        break;
                    case 1:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne;
                        break;
                    case 2:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo;
                        break;
                    case 3:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree;
                        break;
                    case 4:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour;
                        break;
                    case 5:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour . "-" . $TwoChildrenFive;
                        break;
                    case 6:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour . "-" . $TwoChildrenFive . "-" . $TwoChildrenSix;
                        break;
                }

                switch ($childrenSelectTree) {

                    case 0:
                        $distribucion .= "!" . $adultsSelector3;
                        break;
                    case 1:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne;
                        break;
                    case 2:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo;
                        break;
                    case 3:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree;
                        break;
                    case 4:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree . "-" . $TreeChildrenFour;
                        break;
                    case 5:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree . "-" . $TreeChildrenFour . "-" . $TreeChildrenFive;
                        break;
                    case 6:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree . "-" . $TreeChildrenFour . "-" . $TreeChildrenFive . "-" . $TreeChildrenSix;
                        break;
                }
            } elseif ($habitacionesCant == 4) {

                switch ($childrenSelectTwo) {

                    case 0:
                        $distribucion .= "!" . $adultsSelector2;
                        break;

                    case 1:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne;
                        break;
                    case 2:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo;
                        break;
                    case 3:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree;
                        break;
                    case 4:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour;
                        break;
                    case 5:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour . "-" . $TwoChildrenFive;
                        break;
                    case 6:
                        $distribucion .= "!" . $adultsSelector2 . "-" . $TwoChildrenOne . "-" . $TwoChildrenTwo . "-" . $TwoChildrenTree . "-" . $TwoChildrenFour . "-" . $TwoChildrenFive . "-" . $TwoChildrenSix;
                        break;
                }

                switch ($childrenSelectTree) {

                    case 0:
                        $distribucion .= "!" . $adultsSelector3;
                        break;
                    case 1:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne;
                        break;
                    case 2:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo;
                        break;
                    case 3:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree;
                        break;
                    case 4:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree . "-" . $TreeChildrenFour;
                        break;
                    case 5:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree . "-" . $TreeChildrenFour . "-" . $TreeChildrenFive;
                        break;
                    case 6:
                        $distribucion .= "!" . $adultsSelector3 . "-" . $TreeChildrenOne . "-" . $TreeChildrenTwo . "-" . $TreeChildrenTree . "-" . $TreeChildrenFour . "-" . $TreeChildrenFive . "-" . $TreeChildrenSix;
                        break;
                }
                switch ($childrenSelectFour) {
                    case 0:
                        $distribucion .= "!" . $adultsSelector4;
                        break;
                    case 1:
                        $distribucion .= "!" . $adultsSelector4 . "-" . $FourChildrenOne;
                        break;
                    case 2:
                        $distribucion .= "!" . $adultsSelector4 . "-" . $FourChildrenOne . "-" . $FourChildrenTwo;
                        break;
                    case 3:
                        $distribucion .= "!" . $adultsSelector4 . "-" . $FourChildrenOne . "-" . $FourChildrenTwo . "-" . $FourChildrenTree;
                        break;
                    case 4:
                        $distribucion .= "!" . $adultsSelector4 . "-" . $FourChildrenOne . "-" . $FourChildrenTwo . "-" . $FourChildrenTree . "-" . $FourChildrenFour;
                        break;
                    case 5:
                        $distribucion .= "!" . $adultsSelector4 . "-" . $FourChildrenOne . "-" . $FourChildrenTwo . "-" . $FourChildrenTree . "-" . $FourChildrenFour . "-" . $FourChildrenFive;
                        break;
                    case 6:
                        $distribucion .= "!" . $adultsSelector4 . "-" . $FourChildrenOne . "-" . $FourChildrenTwo . "-" . $FourChildrenTree . "-" . $FourChildrenFour . "-" . $FourChildrenFive . "-" . $FourChildrenSix;
                        break;
                }
            }
        }
        return $distribucion;
    }

}
