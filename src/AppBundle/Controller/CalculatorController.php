<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\CalculatorService;

class CalculatorController extends Controller
{
    /**
     * @Route("/", name="app_calculator_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render(':default:index.html.twig');
    }

    /**
     * @Route("/showSum", name="app_calculator_showsum")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSumAction()
    {
        $calcAction = 'app_calculator_dosum';
        return  $this->render(':default:calcform.html.twig', ['action' => $calcAction]);
    }

    /**
     * @Route("/showSubstract", name="app_calculator_showSubstract")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showSubstractAction()
    {
        $calcAction = 'app_calculator_dosubstract';
        return  $this->render(':default:calcform.html.twig', ['action' => $calcAction]);
    }

    /**
     * @Route("/doSum", name="app_calculator_dosum")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doSumAction(Request $request)
    {
        $op1 = $request->request->get('op1');
        $op2 = $request->request->get('op2');

        /**
         * @var CalculatorService $calculator
         */
        $calculator = $this->get('calculator');
        $result = $calculator->sum($op1, $op2);
        
        return  $this->render(':default:result.html.twig',
            ['result' => $result,
             'op1' => $op1,
             'op2' => $op2,
             'operator' => '+',
            ]);
    }

    /**
     * @Route("/doSubstract", name="app_calculator_dosubstract")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doSubstractAction(Request $request)
    {
        $op1 = $request->request->get('op1');
        $op2 = $request->request->get('op2');

        /**
         * @var CalculatorService $calculator
         */
        $calculator = $this->get('calculator');
        $result = $calculator->substract($op1, $op2);

        return  $this->render(':default:result.html.twig',
            ['result' => $result,
                'op1' => $op1,
                'op2' => $op2,
                'operator' => '-',
            ]);
    }

}
