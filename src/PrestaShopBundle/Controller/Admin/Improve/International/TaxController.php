<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShopBundle\Controller\Admin\Improve\International;

use PrestaShop\PrestaShop\Core\Form\FormHandlerInterface;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Security\Annotation\DemoRestricted;
use PrestaShop\PrestaShop\Core\Search\Filters\TaxFilters;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TaxController is responsible for handling "Improve > International > Taxes" page.
 */
class TaxController extends FrameworkBundleAdminController
{
    /**
     * Show taxes page.
     *
     * @AdminSecurity(
     *     "is_granted('read', request.get('_legacy_controller'))",
     *     redirectRoute="admin_tax_index"
     * )
     *
     * @param TaxFilters $filters
     *
     * @return Response
     */
    public function indexAction(TaxFilters $filters)
    {
        $taxGridFactory = $this->get('prestashop.core.grid.factory.tax');
        $taxGrid = $taxGridFactory->getGrid($filters);
        $gridPresenter = $this->get('prestashop.core.grid.presenter.grid_presenter');

        return $this->render('@PrestaShop/Admin/Improve/International/Tax/index.html.twig', [
            'taxGrid' => $gridPresenter->present($taxGrid),
        ]);
    }

    /**
     * Process tax options configuration form.
     *
     * @AdminSecurity(
     *     "is_granted(['update', 'create', 'delete'], request.get('_legacy_controller'))",
     *     redirectRoute="admin_taxes_index"
     * )
     * @DemoRestricted(redirectRoute="admin_taxes_index")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveOptionsAction(Request $request)
    {
        $taxOptionsFormHandler = $this->getTaxOptionsFormHandler();

        $taxOptionsForm = $taxOptionsFormHandler->getForm();
        $taxOptionsForm->handleRequest($request);

        if ($taxOptionsForm->isSubmitted()) {
            $errors = $taxOptionsFormHandler->save($taxOptionsForm->getData());

            if (empty($errors)) {
                $this->addFlash('success', $this->trans('Update successful', 'Admin.Notifications.Success'));

                return $this->redirectToRoute('admin_taxes_index');
            }

            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('admin_taxes_index');
    }

    /**
     * Provides filters functionality.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function searchAction(Request $request)
    {
        $definitionFactory = $this->get('prestashop.core.grid.definition.factory.tax');
        $definitionFactory = $definitionFactory->getDefinition();

        $gridFilterFormFactory = $this->get('prestashop.core.grid.filter.form_factory');
        $searchParametersForm = $gridFilterFormFactory->create($definitionFactory);
        $searchParametersForm->handleRequest($request);

        $filters = [];
        if ($searchParametersForm->isSubmitted()) {
            $filters = $searchParametersForm->getData();
        }

        return $this->redirectToRoute('admin_taxes_index', ['filters' => $filters]);
    }

    /**
     * Edit tax
     *
     * @param $taxId
     *
     * @return RedirectResponse
     */
    public function editAction($taxId)
    {
        //@todo: implement edit
        return $this->redirectToRoute('admin_taxes_index');
    }

    /**
     * Delete tax
     *
     * @param $taxId
     *
     * @return RedirectResponse
     */
    public function deleteAction($taxId)
    {
        //@todo: implement delete action
        return $this->redirectToRoute('admin_taxes_index');
    }

    /**
     * @param $taxId
     *
     * @return RedirectResponse
     */
    public function toggleStatusAction($taxId)
    {
        //@todo: implement toggle action
        return $this->redirectToRoute('admin_taxes_index');
    }

    /**
     * @return FormHandlerInterface
     */
    private function getTaxOptionsFormHandler()
    {
        return $this->get('prestashop.admin.tax_options.form_handler');
    }
}
