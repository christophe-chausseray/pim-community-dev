Feature: Update metric fields
  In order to update products
  As an internal process or any user
  I need to be able to update a metric field of a product

  Scenario: Successfully update a metric field
    Given a "default" catalog configuration
    And the following attributes:
      | code   | type   | localizable | scopable | metricFamily | defaultMetricUnit |
      | weight | metric | yes         | no       | Weight       | KILOGRAM          |
      | width  | metric | no          | yes      | Length       | METER             |
      | height | metric | yes         | yes      | Length       | METER             |
      | depth  | metric | no          | no       | Length       | METER             |
    And the following products:
      | sku  |
      | BOX1 |
      | BOX2 |
      | BOX3 |
      | BOX4 |
      | BOX5 |
    Then I should get the following products after apply the following updater to it:
      | product | actions                                                                                                                                                                                                                                  | result                                                                                                    |
      | BOX1    | [{"type": "set_value", "field": "weight", "value": {"data": "12.4", "unit": "GRAM"}, "locale": "fr_FR", "scope": null}]                                                                                                                  | {"values": {"weight": [{"locale": "fr_FR", "scope": null, "value": {"data": 12.4, "unit": "GRAM"}}]}}     |
      | BOX2    | [{"type": "set_value", "field": "width", "value": {"data": 5, "unit": "METER"}, "locale": null, "scope": "mobile"}]                                                                                                                      | {"values": {"width": [{"locale": null, "scope": "mobile", "value": {"data": 5, "unit": "METER"}}]}}       |
      | BOX3    | [{"type": "set_value", "field": "height", "value": {"data": 5.3, "unit": "METER"}, "locale": "fr_FR", "scope": "mobile"}]                                                                                                                | {"values": {"height": [{"locale": "fr_FR", "scope": "mobile", "value": {"data": 5.3, "unit": "METER"}}]}} |
      | BOX4    | [{"type": "set_value", "field": "depth", "value": {"data": "5", "unit": "CENTIMETER"}, "locale": null, "scope": null}]                                                                                                                   | {"values": {"depth": [{"locale": null, "scope": null, "value": {"data": 5, "unit": "CENTIMETER"}}]}}      |
      | BOX4    | [{"type": "set_value", "field": "depth", "value": {"data": "5", "unit": "CENTIMETER"}, "locale": null, "scope": null}, {"type": "set_value", "field": "depth", "value": {"data": "5", "unit": "METER"}, "locale": null, "scope": null}]  | {"values": {"depth": [{"locale": null, "scope": null, "value": {"data": 5, "unit": "METER"}}]}}           |
      | BOX5    | [{"type": "set_value", "field": "depth", "value": {"data": "5", "unit": "CENTIMETER"}, "locale": null, "scope": null}, {"type": "set_value", "field": "depth", "value": {"data": null, "unit": "METER"}, "locale": null, "scope": null}] | {"values": {"depth": [{"locale": null, "scope": null, "value": {"data": null, "unit": "METER"}}]}}        |
