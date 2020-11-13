<?php
namespace SiteSwitcher\Site\Navigation\Link;

use Omeka\Site\Navigation\Link\LinkInterface;
use Omeka\Stdlib\ErrorStore;
use Omeka\Api\Representation\SiteRepresentation;
use RoleBasedNavigation\Module;

class SiteSwitcher implements LinkInterface
{

    /**
     * Get the link type name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Site switcher'; // @translate
    }

    /**
     * Get the view template used to render the link form.
     *
     * @return string
     */
    public function getFormTemplate()
    {
        return 'site-switcher/navigation-link-form/site-switcher';
    }

    protected function _filterRoleSelectors(array $roleSelectors)
    {
        if (!class_exists(Module)) {
            return $roleSelectors;
        }

        if (in_array(Module::RBN_AUTHENTICATED_USERS, $roleSelectors)) {
            if (in_array(Module::RBN_UNAUTHENTICATED_VISITORS, $roleSelectors)) {
                return []; // equivalent to empty selection
            } else {
                return [
                    Module::RBN_AUTHENTICATED_USERS
                ];
            }
        } elseif (in_array(Module::RBN_UNAUTHENTICATED_VISITORS, $roleSelectors)) {
            return [
                Module::RBN_UNAUTHENTICATED_VISITORS
            ];
        } else {
            return $roleSelectors;
        }
    }

    /**
     * Validate link data.
     *
     * @param array $data
     * @return bool
     */
    public function isValid(array $data, ErrorStore $errorStore)
    {
        return true;
    }

    /**
     * Get the link label.
     *
     * @param array $data
     * @param SiteRepresentation $site
     * @return array
     */
    public function getLabel(array $data, SiteRepresentation $site)
    {
        return isset($data['label']) && '' !== trim($data['label']) ? $data['label'] : null;
    }

    /**
     * Get site slug.
     *
     * @param array $data
     * @param SiteRepresentation $site
     * @return array
     */
    public function getSiteSlug(array $data, SiteRepresentation $site)
    {
        return isset($data['site-slug']) && '' !== trim($data['site-slug']) ? $data['site-slug'] : null;
    }

    /**
     * Translate from site navigation data to Zend Navigation configuration.
     *
     * @param array $data
     * @param SiteRepresentation $site
     * @return array
     */
    public function toZend(array $data, SiteRepresentation $site)
    {
        $path = $_SERVER["REQUEST_URI"];

        $path = preg_replace('/^\/s\/' . $site->slug() .'/', '/s/' . $data['site-slug'], $path);

        $result = [
            'uri' => $path
        ];

        // RoleBasedNavigation compatibility:
        if (isset($data['role_based_navigation_role_ids'])) {
            $result['role_based_navigation_role_ids'] = $data['role_based_navigation_role_ids'];
        }

        return $result;
    }

    /**
     * Translate from site navigation data to jsTree configuration.
     *
     * @param array $data
     * @param SiteRepresentation $site
     * @return array
     */
    public function toJstree(array $data, SiteRepresentation $site)
    {
        $result = [
            'label' => $data['label'],
            'site-slug' => $data['site-slug'],
        ];

        // RoleBasedNavigation compatibility:
        if (isset($data['role_based_navigation_role_ids'])) {
            $result['role_based_navigation_role_ids'] = $data['role_based_navigation_role_ids'];
        }

        return $result;
    }
}
