Feature: Validate completeness of a variant product
  In order to enrich my products
  As a regular user
  I need to get consistent information about completeness

  @acceptance-back
  Scenario:
    And the following attribute:
      | code      | type                         |
      | sku       | pim_catalog_identifier       |
      | color     | pim_catalog_simpleselect     |
      | name      | pim_catalog_text             |
    And the following family:
      | code      |
      | my_family |
    And the product model tshirt and its children "tshirt-black and tshirt-yellow"
    When I add a required value to product model tshirt
    Then the completeness of "tshirt-black and tshirt-yellow" should be impacted
