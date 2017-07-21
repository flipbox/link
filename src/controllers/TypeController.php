<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/link/license
 * @link       https://www.flipboxfactory.com/software/link/
 */

namespace flipbox\link\controllers;

use Craft;
use craft\web\Controller;
use flipbox\link\Link;
use yii\web\HttpException;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class TypeController extends Controller
{

    /**
     * @return Response
     * @throws HttpException
     */
    public function actionSettings(): Response
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        $view = $this->getView();

        $type = Link::getInstance()->getType()->find(
            Craft::$app->getRequest()->getRequiredBodyParam('type')
        );

        if (!$type) {
            throw new HttpException("Type not found");
        }

        // Allow explicit setting of the identifier
        if ($identifier = Craft::$app->getRequest()->getBodyParam('identifier')) {
            $type->setIdentifier($identifier);
        }

        $html = $view->renderTemplate(
            'link/_components/fieldtypes/Link/type',
            [
                'type' => $type,
                'namespace' => Craft::$app->getRequest()->getRequiredBodyParam('namespace')
            ]
        );

        return $this->asJson([
            'label' => $type::displayName(),
            'paneHtml' => $html,
            'headHtml' => $view->getHeadHtml(),
            'footHtml' => $view->getBodyHtml(),
        ]);
    }
}
