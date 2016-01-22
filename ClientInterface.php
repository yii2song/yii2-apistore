<?php

namespace xutongle\apistore;

/**
 * ClientInterface declares basic interface all Api clients should follow.
 *
 * @author Xu Tongle <xutongle@gmail.com>
 * @since 3.0
 */
interface ClientInterface
{

    /**
     * @param string $id service id.
     */
    public function setId($id);

    /**
     * @return string service id
     */
    public function getId();

    /**
     * @return string service name.
     */
    public function getName();

    /**
     * @param string $name service name.
     */
    public function setName($name);

}