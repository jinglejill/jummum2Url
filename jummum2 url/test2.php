<scene sceneID="WYG-sA-AMC">
<objects>
<viewController storyboardIdentifier="ConfirmDisputeViewController" modalTransitionStyle="crossDissolve" modalPresentationStyle="overCurrentContext" id="dOc-Km-btJ" customClass="ConfirmDisputeViewController" sceneMemberID="viewController">
<view key="view" contentMode="scaleToFill" id="nX2-iQ-fqg">
<rect key="frame" x="0.0" y="0.0" width="375" height="667"/>
<autoresizingMask key="autoresizingMask" widthSizable="YES" heightSizable="YES"/>
<subviews>
<view contentMode="scaleToFill" translatesAutoresizingMaskIntoConstraints="NO" id="Rnq-iw-mYB">
<rect key="frame" x="67" y="167" width="240" height="332"/>
<subviews>
<label opaque="NO" userInteractionEnabled="NO" contentMode="left" horizontalHuggingPriority="251" verticalHuggingPriority="251" text="Label" textAlignment="center" lineBreakMode="tailTruncation" numberOfLines="0" baselineAdjustment="alignBaselines" adjustsFontSizeToFit="NO" translatesAutoresizingMaskIntoConstraints="NO" id="8yr-3Q-fek">
<rect key="frame" x="8" y="118" width="224" height="100"/>
<constraints>
<constraint firstAttribute="height" constant="100" id="5mT-R9-bae"/>
</constraints>
<fontDescription key="fontDescription" name="Prompt-Regular" family="Prompt" pointSize="15"/>
<nil key="textColor"/>
<nil key="highlightedColor"/>
</label>
<imageView userInteractionEnabled="NO" contentMode="scaleToFill" horizontalHuggingPriority="251" verticalHuggingPriority="251" image="jummumChef_lightGray.png" translatesAutoresizingMaskIntoConstraints="NO" id="UaJ-KX-KAf">
<rect key="frame" x="90" y="20" width="60" height="60"/>
<constraints>
<constraint firstAttribute="height" constant="60" id="50P-dN-8O9"/>
<constraint firstAttribute="width" constant="60" id="AVL-m0-DWA"/>
</constraints>
</imageView>
<button opaque="NO" contentMode="scaleToFill" contentHorizontalAlignment="center" contentVerticalAlignment="center" buttonType="roundedRect" lineBreakMode="middleTruncation" translatesAutoresizingMaskIntoConstraints="NO" id="xQK-rY-1hf" userLabel="Btn Accept">
<rect key="frame" x="8" y="256" width="224" height="30"/>
<color key="backgroundColor" red="0.3921568627" green="0.86274509799999999" blue="0.7843137255" alpha="1" colorSpace="calibratedRGB"/>
<constraints>
<constraint firstAttribute="height" constant="30" id="jwr-SF-crx"/>
</constraints>
<fontDescription key="fontDescription" name="Prompt-SemiBold" family="Prompt" pointSize="15"/>
<state key="normal" title="ใช่">
<color key="titleColor" white="1" alpha="1" colorSpace="custom" customColorSpace="genericGamma22GrayColorSpace"/>
</state>
<connections>
<action selector="yesDispute:" destination="dOc-Km-btJ" eventType="touchUpInside" id="Mhp-Dr-CXe"/>
</connections>
</button>
<button opaque="NO" contentMode="scaleToFill" contentHorizontalAlignment="center" contentVerticalAlignment="center" buttonType="roundedRect" lineBreakMode="middleTruncation" translatesAutoresizingMaskIntoConstraints="NO" id="4le-tc-doa">
<rect key="frame" x="8" y="294" width="224" height="30"/>
<color key="backgroundColor" red="1" green="0.23529411759999999" blue="0.29411764709999999" alpha="1" colorSpace="calibratedRGB"/>
<constraints>
<constraint firstAttribute="height" constant="30" id="YLc-nt-LYg"/>
</constraints>
<fontDescription key="fontDescription" name="Prompt-SemiBold" family="Prompt" pointSize="15"/>
<state key="normal" title="ไม่">
<color key="titleColor" white="1" alpha="1" colorSpace="custom" customColorSpace="genericGamma22GrayColorSpace"/>
</state>
<connections>
<action selector="noDispute:" destination="dOc-Km-btJ" eventType="touchUpInside" id="Pt5-j9-ZdV"/>
</connections>
</button>
</subviews>
<color key="backgroundColor" white="1" alpha="1" colorSpace="custom" customColorSpace="genericGamma22GrayColorSpace"/>
<constraints>
<constraint firstAttribute="trailing" secondItem="xQK-rY-1hf" secondAttribute="trailing" constant="8" id="4RG-CC-BWs"/>
<constraint firstItem="UaJ-KX-KAf" firstAttribute="top" secondItem="Rnq-iw-mYB" secondAttribute="top" constant="20" id="AVG-XI-FNE"/>
<constraint firstAttribute="width" constant="240" id="JEC-eL-HQt"/>
<constraint firstItem="xQK-rY-1hf" firstAttribute="leading" secondItem="Rnq-iw-mYB" secondAttribute="leading" constant="8" id="JQ3-O3-fCT"/>
<constraint firstItem="4le-tc-doa" firstAttribute="leading" secondItem="Rnq-iw-mYB" secondAttribute="leading" constant="8" id="OAp-0f-z5K"/>
<constraint firstItem="8yr-3Q-fek" firstAttribute="leading" secondItem="Rnq-iw-mYB" secondAttribute="leading" constant="8" id="P0E-OC-qJS"/>
<constraint firstAttribute="trailing" secondItem="8yr-3Q-fek" secondAttribute="trailing" constant="8" id="W7U-Pl-VAO"/>
<constraint firstAttribute="trailing" secondItem="4le-tc-doa" secondAttribute="trailing" constant="8" id="aqx-KH-HzE"/>
<constraint firstAttribute="bottom" secondItem="4le-tc-doa" secondAttribute="bottom" constant="8" id="f85-j2-lR0"/>
<constraint firstAttribute="height" constant="332" id="ggN-rY-yjs"/>
<constraint firstItem="UaJ-KX-KAf" firstAttribute="centerX" secondItem="Rnq-iw-mYB" secondAttribute="centerX" id="o3W-xc-Lee"/>
<constraint firstItem="xQK-rY-1hf" firstAttribute="top" secondItem="8yr-3Q-fek" secondAttribute="bottom" constant="38" id="wgu-Yc-gDr"/>
<constraint firstItem="4le-tc-doa" firstAttribute="top" secondItem="xQK-rY-1hf" secondAttribute="bottom" constant="8" id="yjA-Pk-Bf1"/>
</constraints>
</view>
</subviews>
<color key="backgroundColor" white="0.0" alpha="0.5" colorSpace="custom" customColorSpace="genericGamma22GrayColorSpace"/>
<constraints>
<constraint firstItem="Rnq-iw-mYB" firstAttribute="centerY" secondItem="nX2-iQ-fqg" secondAttribute="centerY" id="9pE-eR-gsv"/>
<constraint firstItem="Rnq-iw-mYB" firstAttribute="centerX" secondItem="nX2-iQ-fqg" secondAttribute="centerX" id="skM-rk-h4W"/>
</constraints>
<viewLayoutGuide key="safeArea" id="iwu-U7-O5p"/>
</view>
<connections>
<outlet property="btnCancel" destination="4le-tc-doa" id="n2C-2c-kzV"/>
<outlet property="btnConfirm" destination="xQK-rY-1hf" id="GcW-mD-ivo"/>
<outlet property="lblDisputeMessage" destination="8yr-3Q-fek" id="jgX-zY-hw2"/>
<outlet property="lblDisputeMessageHeight" destination="5mT-R9-bae" id="Y2H-AB-ccs"/>
<outlet property="vwAlert" destination="Rnq-iw-mYB" id="ATF-gM-KKJ"/>
<outlet property="vwAlertHeight" destination="ggN-rY-yjs" id="peh-Gs-yQv"/>
<segue destination="ioj-Ro-JdP" kind="unwind" identifier="segUnwindToOrderDetail" unwindAction="unwindToOrderDetail:" id="7Bw-iO-dbc"/>
<segue destination="z4l-rZ-sry" kind="show" identifier="segDisputeForm" id="8d7-Y9-TLl"/>
</connections>
</viewController>
<placeholder placeholderIdentifier="IBFirstResponder" id="TUg-yJ-IMm" userLabel="First Responder" sceneMemberID="firstResponder"/>
<exit id="ioj-Ro-JdP" userLabel="Exit" sceneMemberID="exit"/>
</objects>
<point key="canvasLocation" x="4156" y="-201.04947526236884"/>
</scene>
